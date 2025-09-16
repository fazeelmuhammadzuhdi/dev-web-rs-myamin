-- AdminServer.lua
-- Admin sesi (sementara). Owner/whitelist boleh give/revoke. Tanpa noclip.
-- Fitur server: goto/bring/freeze/unfreeze/kick(reason), spectate trigger (kamera di client), invis server-side, admin chat, player list label (ADMIN/HELPER).
-- Optimasi: inisialisasi Remote lebih awal, rate limit anti-spam.

local Players = game:GetService("Players")
local ReplicatedStorage = game:GetService("ReplicatedStorage")

-- Remotes siap sejak awal
local remote = ReplicatedStorage:FindFirstChild("AdminEvent")
if not remote then
    remote = Instance.new("RemoteEvent")
    remote.Name = "AdminEvent"
    remote.Parent = ReplicatedStorage
end

-- Opsional: folder Overhead agar tidak ada infinite yield jika ada listener lain
local overhead = ReplicatedStorage:FindFirstChild("Overhead")
if not overhead then
    overhead = Instance.new("Folder")
    overhead.Name = "Overhead"
    overhead.Parent = ReplicatedStorage
end
if not overhead:FindFirstChild("HideForAll") then
    local ev = Instance.new("RemoteEvent")
    ev.Name = "HideForAll"
    ev.Parent = overhead
end
if not overhead:FindFirstChild("ShowForAll") then
    local ev = Instance.new("RemoteEvent")
    ev.Name = "ShowForAll"
    ev.Parent = overhead
end

print("[AdminServer] Remotes ready")

-- Owner/Whitelist
local OWNER_IDS = {
    7856281988, -- Owner utama
}
local OWNER_SET: {[number]: boolean} = {}
for _, id in ipairs(OWNER_IDS) do OWNER_SET[id] = true end

local function isOwner(p: Player): boolean
    return OWNER_SET[p.UserId] == true
end

local SESSION_ADMINS: {[number]: boolean} = {}

local function isAdmin(p: Player): boolean
    return isOwner(p) or SESSION_ADMINS[p.UserId] == true
end

-- Rate limit per pemain per command
local RATE: {[number]: {[string]: number}} = {}
local function rateLimit(p: Player, cmd: string, gap: number?): boolean
    gap = gap or 0.25
    local now = os.clock()
    RATE[p.UserId] = RATE[p.UserId] or {}
    local last = RATE[p.UserId][cmd] or 0
    if now - last < gap then return false end
    RATE[p.UserId][cmd] = now
    return true
end

-- Broadcast daftar pemain (DisplayName + label ADMIN/HELPER)
local function broadcastPlayerList()
    local list = {}
    for _, plr in ipairs(Players:GetPlayers()) do
        local label = nil
        if isOwner(plr) then
            label = "ADMIN"
        elseif SESSION_ADMINS[plr.UserId] then
            label = "HELPER"
        end
        table.insert(list, { name = plr.Name, display = plr.DisplayName, label = label })
    end
    for _, plr in ipairs(Players:GetPlayers()) do
        remote:FireClient(plr, { t = "plist", list = list })
    end
end

Players.PlayerAdded:Connect(function(p)
    -- Owner selalu admin di sisi UI
    if isOwner(p) then
        remote:FireClient(p, { t = "admin", on = true, owner = true })
    else
        remote:FireClient(p, { t = "admin", on = SESSION_ADMINS[p.UserId] == true, owner = false })
    end
    broadcastPlayerList()
end)

Players.PlayerRemoving:Connect(function(p)
    SESSION_ADMINS[p.UserId] = nil
    RATE[p.UserId] = nil
    broadcastPlayerList()
end)

-- Invis server-side (benar-benar tidak terlihat oleh pemain lain)
type InvisState = { parts: {[Instance]: any}, humDisplayType: any }
local INVIS_STATE: {[number]: InvisState} = {}

local function setInvisible(p: Player, on: boolean)
    local character = p.Character
    if not character then return end
    local hum = character:FindFirstChildOfClass("Humanoid")
    if not hum then return end

    if on then
        if INVIS_STATE[p.UserId] then return end
        local state: InvisState = { parts = {}, humDisplayType = hum.DisplayDistanceType }
        INVIS_STATE[p.UserId] = state
        hum.DisplayDistanceType = Enum.HumanoidDisplayDistanceType.None
        for _, inst in ipairs(character:GetDescendants()) do
            if inst:IsA("BasePart") then
                state.parts[inst] = inst.Transparency
                inst.Transparency = 1
                inst.CanCollide = false
                inst.CanQuery = false
                inst.CanTouch = false
            elseif inst:IsA("Decal") then
                state.parts[inst] = inst.Transparency
                inst.Transparency = 1
            elseif inst:IsA("Accessory") and inst:FindFirstChild("Handle") then
                local h = inst.Handle
                state.parts[h] = h.Transparency
                h.Transparency = 1
                h.CanCollide = false
                h.CanQuery = false
                h.CanTouch = false
            end
        end
        -- Beri tahu semua klien untuk menyembunyikan overhead/nametag kustom milik pemain ini
        for _, plr in ipairs(Players:GetPlayers()) do
            remote:FireClient(plr, { t = "ovh", userId = p.UserId, hide = true })
        end
    else
        local state = INVIS_STATE[p.UserId]
        if not state then return end
        INVIS_STATE[p.UserId] = nil
        if hum then
            if state.humDisplayType ~= nil then
                hum.DisplayDistanceType = state.humDisplayType
            else
                hum.DisplayDistanceType = Enum.HumanoidDisplayDistanceType.Viewer
            end
        end
        for inst, orig in pairs(state.parts) do
            if inst and inst.Parent then
                if inst:IsA("BasePart") then
                    inst.Transparency = orig
                    inst.CanCollide = true
                    inst.CanQuery = true
                    inst.CanTouch = true
                elseif inst:IsA("Decal") then
                    inst.Transparency = orig
                end
            end
        end
        -- Tampilkan kembali overhead kustom
        for _, plr in ipairs(Players:GetPlayers()) do
            remote:FireClient(plr, { t = "ovh", userId = p.UserId, hide = false })
        end
    end
end

-- Util: cari player by Name atau DisplayName
local function findPlayerByName(name: string | nil): Player?
    if not name or name == "" then return nil end
    local lname = string.lower(name)
    for _, plr in ipairs(Players:GetPlayers()) do
        if string.lower(plr.Name) == lname or string.lower(plr.DisplayName) == lname then
            return plr
        end
    end
    return nil
end

-- Aksi
local function doGoto(sender: Player, targetName: string)
    local target = findPlayerByName(targetName)
    if not (target and target.Character and sender.Character) then return end
    local hrpT = target.Character:FindFirstChild("HumanoidRootPart")
    local hrpS = sender.Character:FindFirstChild("HumanoidRootPart")
    if not (hrpT and hrpS) then return end
    hrpS.CFrame = hrpT.CFrame + hrpT.CFrame.LookVector * 3 + Vector3.new(0, 2, 0)
end

local function doBring(sender: Player, targetName: string)
    local target = findPlayerByName(targetName)
    if not (target and target.Character and sender.Character) then return end
    local hrpT = target.Character:FindFirstChild("HumanoidRootPart")
    local hrpS = sender.Character:FindFirstChild("HumanoidRootPart")
    if not (hrpT and hrpS) then return end
    hrpT.CFrame = hrpS.CFrame + hrpS.CFrame.LookVector * 3 + Vector3.new(0, 2, 0)
end

local function doFreeze(sender: Player, targetName: string, on: boolean)
    local target = findPlayerByName(targetName)
    if not (target and target.Character) then return end
    local hum = target.Character:FindFirstChildOfClass("Humanoid")
    if not hum then return end
    if on then
        hum.PlatformStand = true
        for _, p in ipairs(target.Character:GetDescendants()) do
            if p:IsA("BasePart") then p.Anchored = true end
        end
    else
        hum.PlatformStand = false
        for _, p in ipairs(target.Character:GetDescendants()) do
            if p:IsA("BasePart") then p.Anchored = false end
        end
    end
end

local function doKick(sender: Player, targetName: string, reason: string | nil)
    local target = findPlayerByName(targetName)
    if not target then return end
    target:Kick(reason and tostring(reason) or "Kicked")
end

-- Remote handler
remote.OnServerEvent:Connect(function(sender: Player, payload)
    if typeof(payload) ~= "table" then return end
    local t = payload.t
    if t == "cmd" then
        local cmd: string = payload.cmd
        if not rateLimit(sender, cmd, 0.3) then return end

        if cmd == "give" then
            if not isOwner(sender) then return end
            local target = findPlayerByName(payload.target)
            if target and not isOwner(target) then
                SESSION_ADMINS[target.UserId] = true
                remote:FireClient(target, { t = "admin", on = true, owner = false })
                remote:FireClient(sender, { t = "notify", msg = "Gave admin to " .. target.DisplayName })
                broadcastPlayerList()
            end
            return
        elseif cmd == "revoke" then
            if not isOwner(sender) then return end
            local target = findPlayerByName(payload.target)
            if target then
                SESSION_ADMINS[target.UserId] = nil
                remote:FireClient(target, { t = "admin", on = false, owner = false })
                remote:FireClient(sender, { t = "notify", msg = "Revoked admin from " .. target.DisplayName })
                broadcastPlayerList()
            end
            return
        end

        if not isAdmin(sender) then return end

        if cmd == "goto" then
            doGoto(sender, payload.target)
        elseif cmd == "bring" then
            doBring(sender, payload.target)
        elseif cmd == "freeze" then
            doFreeze(sender, payload.target, true)
        elseif cmd == "unfreeze" then
            doFreeze(sender, payload.target, false)
        elseif cmd == "kick" then
            doKick(sender, payload.target, payload.reason)
        elseif cmd == "spectate" then
            remote:FireClient(sender, { t = "spectate", target = payload.target })
        elseif cmd == "invis" then
            setInvisible(sender, true)
            remote:FireClient(sender, { t = "notify", msg = "Invisible ON" })
        elseif cmd == "vis" then
            setInvisible(sender, false)
            remote:FireClient(sender, { t = "notify", msg = "Invisible OFF" })
        elseif cmd == "fly" then
            remote:FireClient(sender, { t = "fly", on = true })
        elseif cmd == "unfly" then
            remote:FireClient(sender, { t = "fly", on = false })
        end

    elseif t == "chat" then
        if not isAdmin(sender) then return end
        if not rateLimit(sender, "chat", 0.25) then return end
        local msg = tostring(payload.msg or "")
        if msg == "" then return end
        for _, plr in ipairs(Players:GetPlayers()) do
            if isAdmin(plr) then
                remote:FireClient(plr, { t = "chat", from = sender.DisplayName, msg = msg })
            end
        end
    end
end)

