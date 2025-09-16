-- CarryServer.lua
-- Ready-to-use: Carry system where carried players still take damage (fall/area/projectiles).
-- Key: Do NOT use PlatformStand/Physics during carry; keep Humanoid simulated (Running).

local Players = game:GetService("Players")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local RunService = game:GetService("RunService")

-- ============================= CONFIG ============================= --
local CONFIG = {
    CARRY_ANIM_CARRIER = "rbxassetid://106628053522111",
    CARRY_ANIM_CARRIED = "rbxassetid://73677695199247",
    CARRY_OFFSET = CFrame.new(0, 0.6, 1.2) * CFrame.Angles(0, math.rad(180), 0),
    MAX_DISTANCE = 10,
    DEBOUNCE_TIME = 0.75,
}

-- ============================= REMOTES ============================= --
local Events = ReplicatedStorage:FindFirstChild("InteractEvents")
if not Events then
    Events = Instance.new("Folder")
    Events.Name = "InteractEvents"
    Events.Parent = ReplicatedStorage
end

local function ensureRemote(name: string)
    local r = Events:FindFirstChild(name)
    if not r then
        r = Instance.new("RemoteEvent")
        r.Name = name
        r.Parent = Events
    end
    return r
end

local CarryRequest = ensureRemote("CarryRequest")
local CarryAction = ensureRemote("CarryAction")
local CarryRelease = ensureRemote("CarryRelease")
local SyncDance = ensureRemote("SyncDance")

-- ============================= UTIL ============================= --
local function root(plr: Player)
    local c = plr.Character
    return c and c:FindFirstChild("HumanoidRootPart") or nil
end

local function hum(plr: Player)
    local c = plr.Character
    return c and c:FindFirstChildOfClass("Humanoid") or nil
end

local function tooFar(a: Player, b: Player, maxStuds: number?)
    maxStuds = maxStuds or CONFIG.MAX_DISTANCE
    local ra, rb = root(a), root(b)
    if not ra or not rb then return true end
    return (ra.Position - rb.Position).Magnitude > maxStuds
end

-- Debounce per-player
local debounceData: {[number]: {[string]: number}} = {}
local function rateLimit(plr: Player, key: string, window: number?)
    window = window or CONFIG.DEBOUNCE_TIME
    local now = tick()
    local tab = debounceData[plr.UserId]
    if not tab then tab = {}; debounceData[plr.UserId] = tab end
    local last = tab[key]
    if last and (now - last) < window then return false end
    tab[key] = now
    return true
end

Players.PlayerRemoving:Connect(function(plr)
    debounceData[plr.UserId] = nil
end)

-- ============================= STATE ============================= --
-- active[carriedUserId] = { carrier, carried, original = {ws, jp, ar}, hbConn }
local active: {[number]: {carrier: Player, carried: Player, original: {ws: number, jp: number, ar: boolean}, hbConn: RBXScriptConnection}?} = {}

local function isCarrierBusy(plr: Player)
    for _, st in pairs(active) do
        if st and st.carrier == plr then return true end
    end
    return false
end

local function isBeingCarried(plr: Player)
    return active[plr.UserId] ~= nil
end

local function findActiveByPlayer(plr: Player)
    for _, st in pairs(active) do
        if st and (st.carrier == plr or st.carried == plr) then
            return st
        end
    end
    return nil
end

-- ============================= CORE ============================= --
local function releaseWeld(plr: Player)
    local rr = plr.Character and plr.Character:FindFirstChild("HumanoidRootPart")
    if not rr then return end
    for _, ch in ipairs(rr:GetChildren()) do
        if ch:IsA("WeldConstraint") and ch.Name == "CarryWeld" then ch:Destroy() end
    end
    rr.Massless = false
end

local function releaseCarry(carrier: Player?, carried: Player?)
    if carried and carried.Character then
        local st = active[carried.UserId]
        releaseWeld(carried)
        local rh = hum(carried)
        if rh then
            rh.Sit = false
            rh.PlatformStand = false
            if st and st.original then
                rh.AutoRotate = st.original.ar
                if st.original.ws ~= nil then rh.WalkSpeed = st.original.ws end
                if st.original.jp ~= nil then rh.JumpPower = st.original.jp end
            else
                rh.AutoRotate = true
            end
            rh:ChangeState(Enum.HumanoidStateType.Running)
        end
        if st and st.hbConn then st.hbConn:Disconnect() end
        active[carried.UserId] = nil
    end
    if carrier and carried then
        CarryAction:FireAllClients("Stop", carrier, carried)
    end
end

-- ============================= REQUEST ============================= --
CarryRequest.OnServerEvent:Connect(function(fromPlayer: Player, targetPlayer)
    if typeof(targetPlayer) ~= "Instance" or not targetPlayer:IsA("Player") then return end
    if fromPlayer == targetPlayer then return end
    if not rateLimit(fromPlayer, "CarryRequest") then return end

    if isBeingCarried(fromPlayer) then
        CarryAction:FireClient(fromPlayer, "YouAreCarried")
        return
    end
    if isCarrierBusy(fromPlayer) then
        CarryAction:FireClient(fromPlayer, "CarrierBusy")
        return
    end
    if active[targetPlayer.UserId] then
        CarryAction:FireClient(fromPlayer, "TargetBusy", targetPlayer)
        return
    end
    if tooFar(fromPlayer, targetPlayer, CONFIG.MAX_DISTANCE) then
        CarryAction:FireClient(fromPlayer, "TooFar", targetPlayer)
        return
    end
    CarryAction:FireClient(fromPlayer, "RequestSent", targetPlayer)
    CarryAction:FireClient(targetPlayer, "Request", fromPlayer)
end)

-- ============================= ACTION ============================= --
CarryAction.OnServerEvent:Connect(function(sender: Player, action, other)
    if action == "Accept" then
        local carrier: Player = other
        local carried: Player = sender
        if typeof(carrier) ~= "Instance" or not carrier:IsA("Player") then return end

        if isBeingCarried(carrier) then
            CarryAction:FireClient(sender, "CarrierIsCarried", carrier)
            return
        end
        if isCarrierBusy(carrier) or active[carried.UserId] then
            CarryAction:FireClient(sender, "Busy")
            return
        end

        local cr, rr, ch, rh = root(carrier), root(carried), hum(carrier), hum(carried)
        if not cr or not rr or not ch or not rh then return end
        if tooFar(carrier, carried, CONFIG.MAX_DISTANCE) then
            CarryAction:FireClient(sender, "TooFar", other)
            CarryAction:FireClient(other, "TooFar", sender)
            return
        end

        -- Setup carry (NO PlatformStand / NO Physics)
        releaseWeld(carried)
        rr.CFrame = cr.CFrame * CONFIG.CARRY_OFFSET
        local weld = Instance.new("WeldConstraint")
        weld.Name = "CarryWeld"
        weld.Part0 = rr
        weld.Part1 = cr
        weld.Parent = rr
        rr.Massless = true

        local original = { ws = rh.WalkSpeed, jp = rh.JumpPower, ar = rh.AutoRotate }
        rh.AutoRotate = false
        rh.WalkSpeed = 0
        rh.JumpPower = 0
        rh.Sit = false
        rh.PlatformStand = false
        rh:ChangeState(Enum.HumanoidStateType.Running)

        active[carried.UserId] = {
            carrier = carrier,
            carried = carried,
            original = original,
            hbConn = RunService.Heartbeat:Connect(function()
                local rrc = carried.Character and carried.Character:FindFirstChild("HumanoidRootPart")
                if rrc then rrc.Massless = true end
                local h = hum(carried)
                if h then
                    -- keep movement disabled without disabling humanoid physics
                    if h.WalkSpeed ~= 0 then h.WalkSpeed = 0 end
                    if h.JumpPower ~= 0 then h.JumpPower = 0 end
                end
            end),
        }

        CarryAction:FireAllClients("Start", carrier, carried, {
            carrierAnimId = CONFIG.CARRY_ANIM_CARRIER,
            carriedAnimId = CONFIG.CARRY_ANIM_CARRIED,
        })

        ch.Died:Once(function() releaseCarry(carrier, carried) end)
        rh.Died:Once(function() releaseCarry(carrier, carried) end)

    elseif action == "Deny" then
        if typeof(other) == "Instance" and other:IsA("Player") then
            CarryAction:FireClient(other, "Denied", sender)
        end

    elseif action == "Stop" then
        if typeof(other) == "Instance" and other:IsA("Player") then
            releaseCarry(sender, other)
            releaseCarry(other, sender)
        end
    end
end)

-- ============================= RELEASE ============================= --
CarryRelease.OnServerEvent:Connect(function(player: Player, other)
    if typeof(other) ~= "Instance" or not other:IsA("Player") then return end
    releaseCarry(player, other)
    releaseCarry(other, player)
end)

-- ============================= CLEANUP ON CHARACTER ============================= --
Players.PlayerAdded:Connect(function(plr)
    plr.CharacterRemoving:Connect(function()
        local st = findActiveByPlayer(plr)
        if st then
            releaseCarry(st.carrier, st.carried)
        end
    end)
end)

Players.PlayerRemoving:Connect(function(plr)
    local st = findActiveByPlayer(plr)
    if st then
        releaseCarry(st.carrier, st.carried)
    end
end)

print("[CarryServer] Ready: damage preserved while carried")

