-- CarryClient.lua
-- Ready-to-use client: requests, accept/deny, animations, and mobile-friendly notification.

local Players = game:GetService("Players")
local StarterGui = game:GetService("StarterGui")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local RunService = game:GetService("RunService")
local UserInputService = game:GetService("UserInputService")

local LocalPlayer = Players.LocalPlayer
local Events = ReplicatedStorage:WaitForChild("InteractEvents")

local CarryRequest = Events:WaitForChild("CarryRequest")
local CarryAction  = Events:WaitForChild("CarryAction")
local CarryRelease = Events:WaitForChild("CarryRelease")
local SyncDance    = Events:WaitForChild("SyncDance")

StarterGui:SetCore("AvatarContextMenuEnabled", true)
pcall(function()
    StarterGui:SetCore("RemoveAvatarContextMenuOption", Enum.AvatarContextMenuOption.Emote)
end)

-- Debounce
local cd = {carry=0, sync=0}
local function onCD(key, secs)
    local now = os.clock()
    if now < (cd[key] or 0) then
        local left = math.max(0, math.floor(cd[key] - now))
        StarterGui:SetCore("SendNotification", {Title="Cooldown", Text=("Tunggu %ds"):format(left), Duration=2})
        return true
    end
    cd[key] = now + secs
    return false
end

local function notify(t, d)
    StarterGui:SetCore("SendNotification", {Title="Interact", Text=t, Duration=d or 3})
end

-- Anim helpers
local activeCarry = {}
local function getHumanoid(char) return char and char:FindFirstChildOfClass("Humanoid") end
local function getAnimator(char) local h = getHumanoid(char) return h and h:FindFirstChildOfClass("Animator") or nil, h end
local function ensureAnimator(h) return h and (h:FindFirstChildOfClass("Animator") or h:WaitForChild("Animator", 2)) or nil end
local function stopCarryAnim(h)
    local tr = h and activeCarry[h]
    if tr then tr:Stop(0.15) activeCarry[h] = nil end
end
local function tryPlay(animator, id)
    if not id or id == "" then return nil end
    local a = Instance.new("Animation")
    a.AnimationId = id
    local ok, tr = pcall(function() return animator:LoadAnimation(a) end)
    if not ok or not tr then return nil end
    tr.Looped = true
    tr.Priority = Enum.AnimationPriority.Action
    tr:Play(0)
    return tr
end
local function playCarryAnim(h, id)
    if not h then return end
    local animator = ensureAnimator(h)
    if not animator then return end
    stopCarryAnim(h)
    activeCarry[h] = tryPlay(animator, id)
end

LocalPlayer.CharacterAdded:Connect(function(char)
    task.defer(function() local _, h = getAnimator(char) stopCarryAnim(h) end)
end)
LocalPlayer.CharacterRemoving:Connect(function(char)
    local h = getHumanoid(char)
    stopCarryAnim(h)
end)

-- Mobile carried notification
local function createCarriedNotification()
    local gui = Instance.new("ScreenGui")
    gui.Name = "CarriedNotification"
    gui.ResetOnSpawn = false
    gui.Parent = LocalPlayer:WaitForChild("PlayerGui")

    local isMobile = UserInputService.TouchEnabled and not UserInputService.KeyboardEnabled
    local fr = Instance.new("Frame")
    fr.Name = "NotificationFrame"
    fr.Size = isMobile and UDim2.new(0, 180, 0, 30) or UDim2.new(0, 220, 0, 35)
    fr.Position = isMobile and UDim2.new(0.5, -90, 0, 10) or UDim2.new(0.5, -110, 0, 15)
    fr.BackgroundColor3 = Color3.fromRGB(255, 100, 100)
    fr.BackgroundTransparency = 0.1
    fr.BorderSizePixel = 0
    fr.Parent = gui
    local corner = Instance.new("UICorner"); corner.CornerRadius = UDim.new(0, 6); corner.Parent = fr
    local stroke = Instance.new("UIStroke"); stroke.Color = Color3.fromRGB(255, 255, 255); stroke.Thickness = 1.5; stroke.Parent = fr
    local txt = Instance.new("TextLabel")
    txt.Size = UDim2.new(1, 0, 1, 0)
    txt.BackgroundTransparency = 1
    txt.Text = "ANDA SEDANG DI CARRY"
    txt.TextColor3 = Color3.fromRGB(255, 255, 255)
    txt.Font = Enum.Font.GothamBold
    txt.TextSize = isMobile and 10 or 12
    txt.TextStrokeTransparency = 0.5
    txt.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
    txt.Parent = fr
end

-- Carry events
CarryAction.OnClientEvent:Connect(function(what, carrier, carried, payload)
    if what == "RequestSent" then
        notify("Request carry terkirim", 2)
    elseif what == "CarrierBusy" then
        notify("Kamu sedang menggendong orang lain", 2)
    elseif what == "TargetBusy" then
        local name = carried and carried.DisplayName or "Target"
        notify(name .. " sedang digendong orang lain", 2)
    elseif what == "YouAreCarried" then
        notify("Anda sedang di carry, tidak bisa menggendong orang lain", 3)
    elseif what == "CarrierIsCarried" then
        local name = carrier and carrier.DisplayName or "Carrier"
        notify(name .. " sedang di carry, tidak bisa menggendong", 3)
    elseif what == "Request" then
        local bind = Instance.new("BindableFunction")
        function bind.OnInvoke(choice)
            if choice == "Accept" then CarryAction:FireServer("Accept", carrier) else CarryAction:FireServer("Deny", carrier) end
        end
        StarterGui:SetCore("SendNotification", { Title = "Carry Request", Text = carrier.DisplayName .. " ingin menggendong kamu", Duration = 6, Button1 = "Accept", Button2 = "Deny", Callback = bind })

    elseif what == "Start" then
        if carrier == LocalPlayer then
            local _, h = getAnimator(LocalPlayer.Character)
            playCarryAnim(h, payload and payload.carrierAnimId)
            notify("Carry dimulai")
        elseif carried == LocalPlayer then
            local _, h = getAnimator(LocalPlayer.Character)
            playCarryAnim(h, payload and payload.carriedAnimId)
            notify("Kamu digendong")
            createCarriedNotification()
        end

    elseif what == "Stop" then
        local _, h = getAnimator(LocalPlayer.Character)
        stopCarryAnim(h)
        local pg = LocalPlayer:WaitForChild("PlayerGui")
        local n = pg:FindFirstChild("CarriedNotification")
        if n then n:Destroy() end

    elseif what == "Denied" then
        if carrier == LocalPlayer then notify("Carry ditolak") end
    elseif what == "TooFar" then
        local name = (carrier and carrier.DisplayName) or (carried and carried.DisplayName) or "Target"
        notify("Terlalu jauh dari " .. name .. " (max 10 studs)", 3)
    end
end)

-- Context menu options
local function addOption(label, bindable)
    StarterGui:SetCore("AddAvatarContextMenuOption", {label, bindable})
end

local carryOpt = Instance.new("BindableEvent")
carryOpt.Event:Connect(function(targetPlayer)
    if not targetPlayer or targetPlayer == LocalPlayer then return end
    if onCD("carry", 0.75) then return end
    CarryRequest:FireServer(targetPlayer)
end)

local uncarryOpt = Instance.new("BindableEvent")
uncarryOpt.Event:Connect(function(targetPlayer)
    if not targetPlayer then return end
    CarryRelease:FireServer(targetPlayer)
end)

addOption("Carry", carryOpt)
addOption("Release Carry", uncarryOpt)

print("[CarryClient] Ready")

