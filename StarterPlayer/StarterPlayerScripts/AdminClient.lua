-- AdminClient.lua (Unified PC + Mobile)
-- Ringan dan rapi: UI responsif (mobile kecil, center), debounce, fly smooth, spectate kamera saja,
-- invis toggle, goto/bring/freeze/unfreeze/kick(reason), admin chat, give/revoke (owner-only), tanpa noclip.

local Players = game:GetService("Players")
local UserInputService = game:GetService("UserInputService")
local RunService = game:GetService("RunService")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local StarterGui = game:GetService("StarterGui")

local LOCAL_PLAYER = Players.LocalPlayer

-- Remote fetch aman (anti infinite yield)
local function getRemote(name: string, timeout: number?)
    timeout = timeout or 10
    local r = ReplicatedStorage:FindFirstChild(name)
    if r then return r end
    r = ReplicatedStorage:WaitForChild(name, timeout)
    if r then return r end
    local got: Instance? = nil
    local conn; conn = ReplicatedStorage.ChildAdded:Connect(function(ch)
        if ch.Name == name then got = ch end
    end)
    task.wait(5)
    if conn then conn:Disconnect() end
    return got
end

local REMOTE: RemoteEvent = getRemote("AdminEvent", 10) :: any
if not REMOTE then
    warn("[AdminClient] AdminEvent not found, will retry in background")
    task.defer(function()
        REMOTE = getRemote("AdminEvent", 20) :: any
        if REMOTE then
            print("[AdminClient] AdminEvent connected (late)")
        end
    end)
end

local function isMobileDevice(): boolean
    local isConsole = UserInputService.GamepadEnabled and not UserInputService.KeyboardEnabled and not UserInputService.MouseEnabled
    if isConsole then return false end -- treat console like PC for UI toggle via F5 if keyboard present
    return UserInputService.TouchEnabled and not UserInputService.KeyboardEnabled
end

local IS_MOBILE = isMobileDevice()

-- Debounce util
local last: {[string]: number} = {}
local function debounce(key: string, gap: number?): boolean
    gap = gap or 0.25
    local now = os.clock()
    local prev = last[key] or 0
    if now - prev < gap then return false end
    last[key] = now
    return true
end

-- Notif aman (tanpa error jika belum siap)
local function notify(msg: string)
    if not msg or msg == "" then return end
    local ok, err = pcall(function()
        StarterGui:SetCore("SendNotification", { Title = "Admin", Text = msg, Duration = 2 })
    end)
    if not ok then
        print("[AdminNotify] ", msg)
    end
end

-- State
local hasAdmin = false
local isOwner = false
local selectedName: string? = nil

-- GUI Root
local gui = Instance.new("ScreenGui")
gui.Name = "AdminUI"
gui.IgnoreGuiInset = true
gui.ResetOnSpawn = false
gui.Parent = LOCAL_PLAYER:WaitForChild("PlayerGui")

-- Mobile open button (hanya muncul jika admin & mobile)
local openBtn = Instance.new("TextButton")
openBtn.Name = "OpenAdmin"
openBtn.Visible = false
openBtn.Size = UDim2.new(0, 32, 0, 32)
openBtn.AnchorPoint = Vector2.new(1, 0.5)
openBtn.Position = UDim2.new(1, -10, 0.5, 0)
openBtn.BackgroundColor3 = Color3.fromRGB(255, 210, 70)
openBtn.Text = "⚙️"
openBtn.TextSize = 16
openBtn.Font = Enum.Font.GothamBold
openBtn.Parent = gui
local openCorner = Instance.new("UICorner")
openCorner.CornerRadius = UDim.new(0, 6)
openCorner.Parent = openBtn

-- Panel utama
local panel = Instance.new("Frame")
panel.Name = "AdminPanel"
panel.AnchorPoint = Vector2.new(0.5, 0.5)
panel.Position = UDim2.new(0.5, 0, 0.5, 0)
panel.BackgroundColor3 = Color3.fromRGB(245, 248, 255)
panel.BorderSizePixel = 0
panel.Parent = gui
local panelCorner = Instance.new("UICorner")
panelCorner.CornerRadius = UDim.new(0, 10)
panelCorner.Parent = panel
local stroke = Instance.new("UIStroke")
stroke.Thickness = 1
stroke.Color = Color3.fromRGB(200, 210, 230)
stroke.Parent = panel

local layoutRoot = Instance.new("UIListLayout")
layoutRoot.FillDirection = Enum.FillDirection.Vertical
layoutRoot.Padding = UDim.new(0, 6)
layoutRoot.Parent = panel

-- Top: target + reason
local top = Instance.new("Frame")
top.BackgroundTransparency = 1
top.Size = UDim2.new(1, -12, 0, 68)
top.Parent = panel
local topPad = Instance.new("UIPadding")
topPad.PaddingLeft = UDim.new(0, 6)
topPad.PaddingRight = UDim.new(0, 6)
topPad.Parent = top

local targetLabel = Instance.new("TextLabel")
targetLabel.BackgroundTransparency = 1
targetLabel.Size = UDim2.new(1, 0, 0, 24)
targetLabel.TextXAlignment = Enum.TextXAlignment.Left
targetLabel.Text = "🎯 Target: -"
targetLabel.TextColor3 = Color3.fromRGB(20, 28, 45)
targetLabel.TextSize = 16
targetLabel.Font = Enum.Font.GothamMedium
targetLabel.Parent = top

local reasonBox = Instance.new("TextBox")
reasonBox.Size = UDim2.new(1, 0, 0, 34)
reasonBox.PlaceholderText = "Reason (untuk Kick)"
reasonBox.Text = ""
reasonBox.ClearTextOnFocus = false
reasonBox.TextSize = 14
reasonBox.Font = Enum.Font.Gotham
reasonBox.TextColor3 = Color3.fromRGB(20, 28, 45)
reasonBox.BackgroundColor3 = Color3.fromRGB(255, 255, 255)
reasonBox.BorderSizePixel = 0
reasonBox.Parent = top
local reasonCorner = Instance.new("UICorner")
reasonCorner.CornerRadius = UDim.new(0, 8)
reasonCorner.Parent = reasonBox

-- Middle: kiri fitur, kanan player list
local mid = Instance.new("Frame")
mid.BackgroundTransparency = 1
mid.Size = UDim2.new(1, -12, 0, 200)
mid.Parent = panel
local midPad = Instance.new("UIPadding")
midPad.PaddingLeft = UDim.new(0, 6)
midPad.PaddingRight = UDim.new(0, 6)
midPad.Parent = mid

local midLayout = Instance.new("UIListLayout")
midLayout.FillDirection = Enum.FillDirection.Horizontal
midLayout.Padding = UDim.new(0, 6)
midLayout.Parent = mid

local left = Instance.new("Frame")
left.BackgroundTransparency = 1
left.Size = UDim2.new(0.6, 0, 1, 0)
left.Parent = mid

local right = Instance.new("Frame")
right.BackgroundTransparency = 1
right.Size = UDim2.new(0.4, 0, 1, 0)
right.Parent = mid

-- Feature grid
local features = Instance.new("Frame")
features.BackgroundTransparency = 1
features.Size = UDim2.new(1, 0, 1, 0)
features.Parent = left
local grid = Instance.new("UIGridLayout")
grid.CellSize = UDim2.new(0.5, -6, 0, 34)
grid.CellPadding = UDim2.new(0, 6, 0, 6)
grid.FillDirectionMaxCells = 2
grid.SortOrder = Enum.SortOrder.LayoutOrder
grid.Parent = features

local function makeBtn(txt: string, color: Color3): TextButton
    local b = Instance.new("TextButton")
    b.Size = UDim2.new(1, 0, 0, 34)
    b.BackgroundColor3 = color
    b.BorderSizePixel = 0
    b.Text = txt
    b.TextColor3 = Color3.fromRGB(20, 28, 45)
    b.TextSize = 14
    b.Font = Enum.Font.GothamBold
    local c = Instance.new("UICorner")
    c.CornerRadius = UDim.new(0, 8)
    c.Parent = b
    local s = Instance.new("UIStroke")
    s.Thickness = 1
    s.Color = Color3.fromRGB(210, 220, 240)
    s.Parent = b
    return b
end

-- Player list
local plist = Instance.new("ScrollingFrame")
plist.Size = UDim2.new(1, 0, 1, 0)
plist.CanvasSize = UDim2.new(0, 0, 0, 0)
plist.ScrollBarThickness = 4
plist.BackgroundColor3 = Color3.fromRGB(255, 255, 255)
plist.BorderSizePixel = 0
plist.Parent = right
local plistCorner = Instance.new("UICorner")
plistCorner.CornerRadius = UDim.new(0, 8)
plistCorner.Parent = plist
local plistLayout = Instance.new("UIListLayout")
plistLayout.Padding = UDim.new(0, 4)
plistLayout.Parent = plist
local function refreshCanvas()
    plist.CanvasSize = UDim2.new(0, 0, 0, plistLayout.AbsoluteContentSize.Y + 8)
end
plistLayout:GetPropertyChangedSignal("AbsoluteContentSize"):Connect(refreshCanvas)

-- Bottom: chat box
local chatBox = Instance.new("TextBox")
chatBox.Size = UDim2.new(1, -12, 0, 34)
chatBox.Text = ""
chatBox.PlaceholderText = "Admin Chat (Enter untuk kirim)"
chatBox.TextSize = 14
chatBox.Font = Enum.Font.Gotham
chatBox.TextColor3 = Color3.fromRGB(20, 28, 45)
chatBox.BackgroundColor3 = Color3.fromRGB(255, 255, 255)
chatBox.BorderSizePixel = 0
chatBox.Parent = panel
local chatCorner = Instance.new("UICorner")
chatCorner.CornerRadius = UDim.new(0, 8)
chatCorner.Parent = chatBox
local chatPad = Instance.new("UIPadding")
chatPad.PaddingLeft = UDim.new(0, 6)
chatPad.PaddingRight = UDim.new(0, 6)
chatPad.Parent = chatBox

-- Responsif
local function applyResponsive()
    if IS_MOBILE then
        panel.Size = UDim2.new(0, 320, 0, 360)
        openBtn.Visible = hasAdmin
        openBtn.Position = UDim2.new(1, -10, 0.5, 0)
        panel.Position = UDim2.new(0.5, 0, 0.5, 0)
        grid.CellSize = UDim2.new(0.5, -6, 0, 34)
        mid.Size = UDim2.new(1, -12, 0, 200)
    else
        panel.Size = UDim2.new(0, 600, 0, 420)
        openBtn.Visible = false
        grid.CellSize = UDim2.new(0.5, -6, 0, 34)
        mid.Size = UDim2.new(1, -12, 0, 240)
    end
end

-- Toggle panel
local panelOpen = false
local function setPanel(open: boolean)
    panel.Visible = open
    panelOpen = open
end
setPanel(false)

-- PC: F5 toggle
UserInputService.InputBegan:Connect(function(input, gpe)
    if gpe then return end
    if input.KeyCode == Enum.KeyCode.F5 and not IS_MOBILE and hasAdmin then
        if not debounce("toggle", 0.25) then return end
        setPanel(not panelOpen)
    end
end)

-- Mobile: ikon toggle
openBtn.MouseButton1Click:Connect(function()
    if not debounce("toggle", 0.25) then return end
    setPanel(not panelOpen)
end)

-- Tombol fitur
local btnSpectate = makeBtn("👁️ Spectate", Color3.fromRGB(255, 230, 120))
btnSpectate.Parent = features
local btnFly = makeBtn("🪽 Fly", Color3.fromRGB(170, 235, 255))
btnFly.Parent = features
local btnInvis = makeBtn("🫥 Invis", Color3.fromRGB(255, 205, 230))
btnInvis.Parent = features
local btnGoto = makeBtn("🛰️ Teleport", Color3.fromRGB(200, 240, 200))
btnGoto.Parent = features
local btnBring = makeBtn("📥 Bring", Color3.fromRGB(210, 230, 255))
btnBring.Parent = features
local btnFreeze = makeBtn("🧊 Freeze", Color3.fromRGB(200, 220, 255))
btnFreeze.Parent = features
local btnUnfreeze = makeBtn("🔥 Unfreeze", Color3.fromRGB(255, 220, 200))
btnUnfreeze.Parent = features
local btnKick = makeBtn("⛔ Kick", Color3.fromRGB(255, 200, 200))
btnKick.Parent = features
local btnGive = makeBtn("🎛️ Give Admin", Color3.fromRGB(210, 255, 210))
btnGive.Parent = features
local btnRevoke = makeBtn("🗑️ Revoke Admin", Color3.fromRGB(255, 210, 210))
btnRevoke.Parent = features
btnGive.Visible = false
btnRevoke.Visible = false

-- Player list builder
local function buildPlayerRow(item)
    local row = Instance.new("TextButton")
    row.Size = UDim2.new(1, -8, 0, 28)
    row.BackgroundColor3 = Color3.fromRGB(245, 248, 255)
    row.BorderSizePixel = 0
    row.TextXAlignment = Enum.TextXAlignment.Left
    local label = item.display
    if item.label == "ADMIN" then
        label = label .. "  (ADMIN)"
    elseif item.label == "HELPER" then
        label = label .. "  (HELPER)"
    end
    row.Text = "  " .. label
    row.TextColor3 = Color3.fromRGB(20, 28, 45)
    row.TextSize = 14
    row.Font = Enum.Font.Gotham
    local c = Instance.new("UICorner")
    c.CornerRadius = UDim.new(0, 6)
    c.Parent = row
    local s = Instance.new("UIStroke")
    s.Thickness = 1
    s.Color = Color3.fromRGB(220, 230, 245)
    s.Parent = row
    row.MouseButton1Click:Connect(function()
        if not debounce("pick", 0.2) then return end
        selectedName = item.name
        targetLabel.Text = "🎯 Target: " .. item.display
    end)
    return row
end

local function refreshPlayerList(list)
    -- Hanya hapus baris pemain, pertahankan layout/helper
    for _, child in ipairs(plist:GetChildren()) do
        if child:IsA("TextButton") then
            child:Destroy()
        end
    end
    for _, item in ipairs(list or {}) do
        local row = buildPlayerRow(item)
        row.Parent = plist
    end
    refreshCanvas()
end

-- Chat send
chatBox.FocusLost:Connect(function(enter)
    if not enter then return end
    if not debounce("chat", 0.25) then return end
    local txt = chatBox.Text
    if txt ~= "" and hasAdmin and REMOTE then
        REMOTE:FireServer({ t = "chat", msg = txt })
        chatBox.Text = ""
    end
end)

-- Fly (smooth, bersih saat OFF)
local isFlying = false
local bv: BodyVelocity? = nil
local bg: BodyGyro? = nil
local saved = { ws = nil :: number?, jp = nil :: number?, autoRotate = true }

local function cleanupFly()
    local char = LOCAL_PLAYER.Character
    local hrp = char and char:FindFirstChild("HumanoidRootPart")
    local hum = char and char:FindFirstChildOfClass("Humanoid")
    if hrp then
        for _, child in ipairs(hrp:GetChildren()) do
            if child:IsA("BodyMover") or child:IsA("BodyGyro") or child:IsA("BodyVelocity") then
                child:Destroy()
            end
        end
        hrp.AssemblyLinearVelocity = Vector3.zero
        hrp.AssemblyAngularVelocity = Vector3.zero
    end
    if hum then
        hum.AutoRotate = saved.autoRotate ~= false
        if saved.ws then hum.WalkSpeed = saved.ws end
        if saved.jp then hum.JumpPower = saved.jp end
        hum.PlatformStand = false
        hum:ChangeState(Enum.HumanoidStateType.Running)
    end
    bv = nil
    bg = nil
end

local function setFly(on: boolean)
    if on == isFlying then return end
    local char = LOCAL_PLAYER.Character
    local hrp = char and char:FindFirstChild("HumanoidRootPart")
    local hum = char and char:FindFirstChildOfClass("Humanoid")
    if not (char and hrp and hum) then return end

    if on then
        isFlying = true
        saved.ws = hum.WalkSpeed
        saved.jp = hum.JumpPower
        saved.autoRotate = hum.AutoRotate
        hum.AutoRotate = false
        hum.WalkSpeed = 0
        hum.JumpPower = 0
        hum.PlatformStand = false

        -- Bersihkan sisa body movers sebelum mulai
        for _, child in ipairs(hrp:GetChildren()) do
            if child:IsA("BodyMover") or child:IsA("BodyGyro") or child:IsA("BodyVelocity") then
                child:Destroy()
            end
        end

        bv = Instance.new("BodyVelocity")
        bv.MaxForce = Vector3.new(1e6, 1e6, 1e6)
        bv.Velocity = Vector3.zero
        bv.Parent = hrp

        bg = Instance.new("BodyGyro")
        bg.MaxTorque = Vector3.new(1e6, 1e6, 1e6)
        bg.P = 1e5
        bg.CFrame = hrp.CFrame
        bg.Parent = hrp

        notify("Fly ON")
    else
        isFlying = false
        cleanupFly()
        notify("Fly OFF")
    end
end

local flySpeed = 60
RunService.RenderStepped:Connect(function(dt)
    if not isFlying then return end
    local char = LOCAL_PLAYER.Character
    local hrp = char and char:FindFirstChild("HumanoidRootPart")
    if not (char and hrp and bv and bg) then return end

    local cam = workspace.CurrentCamera
    local cf = cam.CFrame
    local dir = Vector3.zero
    if UserInputService:IsKeyDown(Enum.KeyCode.W) then dir += Vector3.new(0, 0, -1) end
    if UserInputService:IsKeyDown(Enum.KeyCode.S) then dir += Vector3.new(0, 0, 1) end
    if UserInputService:IsKeyDown(Enum.KeyCode.A) then dir += Vector3.new(-1, 0, 0) end
    if UserInputService:IsKeyDown(Enum.KeyCode.D) then dir += Vector3.new(1, 0, 0) end
    if UserInputService:IsKeyDown(Enum.KeyCode.Space) then dir += Vector3.new(0, 1, 0) end
    if UserInputService:IsKeyDown(Enum.KeyCode.LeftControl) or UserInputService:IsKeyDown(Enum.KeyCode.LeftShift) then dir += Vector3.new(0, -1, 0) end

    local look = cf.LookVector
    local right = cf.RightVector
    local up = Vector3.new(0, 1, 0)
    local move = (right * dir.X) + (look * -dir.Z) + (up * dir.Y)
    if move.Magnitude > 0 then move = move.Unit end

    local targetVel = move * flySpeed
    local currVel = hrp.AssemblyLinearVelocity
    local blend = math.clamp(12 * dt, 0, 1)
    local newVel = currVel:Lerp(targetVel, blend)
    if targetVel.Magnitude < 0.1 then newVel = newVel * 0.85 end
    hrp.AssemblyLinearVelocity = newVel

    if move.Magnitude > 0.01 then
        bg.CFrame = CFrame.new(hrp.Position, hrp.Position + Vector3.new(look.X, 0, look.Z))
    end
end)

-- Spectate (kamera saja, toggle via command)
local isSpectating = false
local originalSubject: Instance? = nil
local function setSpectate(targetName: string?)
    local camera = workspace.CurrentCamera
    if not targetName or targetName == "" then
        if isSpectating and originalSubject then
            camera.CameraSubject = originalSubject
            isSpectating = false
            notify("Spectate OFF")
        end
        return
    end
    local target = Players:FindFirstChild(targetName)
    if not (target and target.Character) then return end
    local hum = target.Character:FindFirstChildOfClass("Humanoid")
    if not hum then return end
    if not isSpectating then
        originalSubject = camera.CameraSubject
        isSpectating = true
        notify("Spectate ON: " .. target.DisplayName)
    end
    camera.CameraSubject = hum
end

-- Quick local invis visual (server tetap atur global)
local function quickLocalInvis(on: boolean)
    local char = LOCAL_PLAYER.Character
    if not char then return end
    for _, inst in ipairs(char:GetDescendants()) do
        if inst:IsA("BasePart") or inst:IsA("Decal") then
            inst.LocalTransparencyModifier = on and 1 or 0
        end
    end
end

-- Actions wiring
btnSpectate.MouseButton1Click:Connect(function()
    if not debounce("spectate", 0.3) then return end
    if not selectedName then notify("Pilih target dulu"); return end
    if REMOTE then REMOTE:FireServer({ t = "cmd", cmd = "spectate", target = selectedName }) end
end)

btnFly.MouseButton1Click:Connect(function()
    if not debounce("fly", 0.2) then return end
    if REMOTE then
        if not isFlying then
            REMOTE:FireServer({ t = "cmd", cmd = "fly" })
        else
            REMOTE:FireServer({ t = "cmd", cmd = "unfly" })
        end
    end
end)

btnInvis.MouseButton1Click:Connect(function()
    if not debounce("invis", 0.3) then return end
    local char = LOCAL_PLAYER.Character
    local head = char and char:FindFirstChild("Head")
    local hintInvisible = head and (head :: any).LocalTransparencyModifier == 1
    quickLocalInvis(not hintInvisible)
    if REMOTE then
        if hintInvisible then
            REMOTE:FireServer({ t = "cmd", cmd = "vis" })
        else
            REMOTE:FireServer({ t = "cmd", cmd = "invis" })
        end
    end
end)

btnGoto.MouseButton1Click:Connect(function()
    if not debounce("goto", 0.25) then return end
    if not selectedName then notify("Pilih target dulu"); return end
    if REMOTE then REMOTE:FireServer({ t = "cmd", cmd = "goto", target = selectedName }) end
end)

btnBring.MouseButton1Click:Connect(function()
    if not debounce("bring", 0.25) then return end
    if not selectedName then notify("Pilih target dulu"); return end
    if REMOTE then REMOTE:FireServer({ t = "cmd", cmd = "bring", target = selectedName }) end
end)

btnFreeze.MouseButton1Click:Connect(function()
    if not debounce("freeze", 0.25) then return end
    if not selectedName then notify("Pilih target dulu"); return end
    if REMOTE then REMOTE:FireServer({ t = "cmd", cmd = "freeze", target = selectedName }) end
end)

btnUnfreeze.MouseButton1Click:Connect(function()
    if not debounce("unfreeze", 0.25) then return end
    if not selectedName then notify("Pilih target dulu"); return end
    if REMOTE then REMOTE:FireServer({ t = "cmd", cmd = "unfreeze", target = selectedName }) end
end)

btnKick.MouseButton1Click:Connect(function()
    if not debounce("kick", 0.5) then return end
    if not selectedName then notify("Pilih target dulu"); return end
    if REMOTE then REMOTE:FireServer({ t = "cmd", cmd = "kick", target = selectedName, reason = reasonBox.Text }) end
end)

btnGive.MouseButton1Click:Connect(function()
    if not debounce("give", 0.5) then return end
    if not selectedName then notify("Pilih target dulu"); return end
    if REMOTE then REMOTE:FireServer({ t = "cmd", cmd = "give", target = selectedName }) end
end)

btnRevoke.MouseButton1Click:Connect(function()
    if not debounce("revoke", 0.5) then return end
    if not selectedName then notify("Pilih target dulu"); return end
    if REMOTE then REMOTE:FireServer({ t = "cmd", cmd = "revoke", target = selectedName }) end
end)

-- Remote events (server -> client)
if REMOTE then
    REMOTE.OnClientEvent:Connect(function(payload)
        if typeof(payload) ~= "table" then return end
        if payload.t == "admin" then
            hasAdmin = payload.on == true
            isOwner = payload.owner == true
            openBtn.Visible = IS_MOBILE and hasAdmin
            btnGive.Visible = isOwner
            btnRevoke.Visible = isOwner
            if not hasAdmin then setPanel(false) end
            notify(hasAdmin and "Admin enabled" or "Admin disabled")
            applyResponsive()
        elseif payload.t == "plist" then
            refreshPlayerList(payload.list or {})
        elseif payload.t == "chat" then
            notify(((payload.from or "?") .. ": " .. (payload.msg or "")))
        elseif payload.t == "notify" then
            notify(payload.msg or "")
        elseif payload.t == "spectate" then
            -- Toggle jika target sama dan sedang ON
            if isSpectating and payload.target and selectedName and string.lower(payload.target) == string.lower(selectedName) then
                setSpectate(nil)
            else
                setSpectate(payload.target)
            end
        elseif payload.t == "fly" then
            setFly(payload.on == true)
        end
    end)
end

-- Init
applyResponsive()
print("[AdminClient] Ready")

