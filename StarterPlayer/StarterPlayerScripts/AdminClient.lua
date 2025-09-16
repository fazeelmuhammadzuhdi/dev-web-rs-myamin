-- AdminClient.lua (Unified PC + Mobile, 3-column layout)
-- Kiri: Player List | Tengah: Fitur Admin | Kanan: Admin Chat
-- Mobile & PC tampilan sama, ukuran disesuaikan (mobile lebih kecil, center). Ikon buka hanya di mobile; PC pakai F5.
-- Fitur: Spectate (kamera), Fly smooth (bersih saat OFF), Invis (server-side + hint lokal), Teleport (goto), Bring, Freeze/Unfreeze, Kick (reason), Give/Revoke (hanya owner), Admin Chat.
-- Optimasi: debounce UI, remote fetch aman, tidak memindah parent layout saat refresh list.

local Players = game:GetService("Players")
local UserInputService = game:GetService("UserInputService")
-- RunService no longer needed (fly removed)
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local StarterGui = game:GetService("StarterGui")

local LOCAL_PLAYER = Players.LocalPlayer

-- Device
local function isMobileDevice(): boolean
    local isConsole = UserInputService.GamepadEnabled and not UserInputService.KeyboardEnabled and not UserInputService.MouseEnabled
    if isConsole then return false end
    return UserInputService.TouchEnabled and not UserInputService.KeyboardEnabled
end
local IS_MOBILE = isMobileDevice()

-- Remote aman
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
    warn("[AdminClient] AdminEvent not found; retrying later")
    task.defer(function()
        REMOTE = getRemote("AdminEvent", 20) :: any
        if REMOTE then print("[AdminClient] AdminEvent connected (late)") end
    end)
end

-- Debounce
local last: {[string]: number} = {}
local function debounce(key: string, gap: number?): boolean
    gap = gap or 0.25
    local now = os.clock()
    local prev = last[key] or 0
    if now - prev < gap then return false end
    last[key] = now
    return true
end

-- Notifikasi
local function notify(msg: string)
    if not msg or msg == "" then return end
    local ok = pcall(function()
        StarterGui:SetCore("SendNotification", { Title = "Admin", Text = msg, Duration = 2 })
    end)
    if not ok then print("[AdminNotify] ", msg) end
end

-- State
local hasAdmin = false
local isOwner = false
local selectedName: string? = nil
local selectedRow: TextButton? = nil

-- Root GUI
local gui = Instance.new("ScreenGui")
gui.Name = "AdminUI"
gui.IgnoreGuiInset = true
gui.ResetOnSpawn = false
gui.Parent = LOCAL_PLAYER:WaitForChild("PlayerGui")

-- Mobile open icon
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
panel.BackgroundColor3 = Color3.fromRGB(16, 18, 22)
panel.BorderSizePixel = 0
panel.Visible = false
panel.Parent = gui
local panelCorner = Instance.new("UICorner")
panelCorner.CornerRadius = UDim.new(0, 10)
panelCorner.Parent = panel
local stroke = Instance.new("UIStroke")
stroke.Thickness = 1
stroke.Color = Color3.fromRGB(60, 70, 90)
stroke.Parent = panel

-- Columns container
local columns = Instance.new("Frame")
columns.BackgroundTransparency = 1
columns.Parent = panel
local colLayout = Instance.new("UIListLayout")
colLayout.FillDirection = Enum.FillDirection.Horizontal
colLayout.Padding = UDim.new(0, 8)
colLayout.Parent = columns
local colPad = Instance.new("UIPadding")
colPad.PaddingLeft = UDim.new(0, 8)
colPad.PaddingRight = UDim.new(0, 8)
colPad.PaddingTop = UDim.new(0, 8)
colPad.PaddingBottom = UDim.new(0, 8)
colPad.Parent = columns

-- Left: Player List
local leftCol = Instance.new("Frame")
leftCol.BackgroundTransparency = 1
leftCol.Parent = columns
local leftTitle = Instance.new("TextLabel")
leftTitle.BackgroundTransparency = 1
leftTitle.Size = UDim2.new(1, 0, 0, 22)
leftTitle.Text = "👥 Players"
leftTitle.TextXAlignment = Enum.TextXAlignment.Left
leftTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
leftTitle.TextSize = 14
leftTitle.Font = Enum.Font.GothamMedium
leftTitle.Parent = leftCol
local playerList = Instance.new("ScrollingFrame")
playerList.Name = "PlayerList"
playerList.BackgroundColor3 = Color3.fromRGB(28, 32, 40)
playerList.BorderSizePixel = 0
playerList.ScrollBarThickness = 4
playerList.Parent = leftCol
local plCorner = Instance.new("UICorner")
plCorner.CornerRadius = UDim.new(0, 8)
plCorner.Parent = playerList
local plLayout = Instance.new("UIListLayout")
plLayout.Padding = UDim.new(0, 4)
plLayout.Parent = playerList
local function refreshPLCanvas()
    playerList.CanvasSize = UDim2.new(0, 0, 0, plLayout.AbsoluteContentSize.Y + 8)
end
plLayout:GetPropertyChangedSignal("AbsoluteContentSize"):Connect(refreshPLCanvas)

-- Center: Features + Reason
local centerCol = Instance.new("Frame")
centerCol.BackgroundTransparency = 1
centerCol.Parent = columns
local centerTop = Instance.new("Frame")
centerTop.BackgroundTransparency = 1
centerTop.Parent = centerCol
local targetLabel = Instance.new("TextLabel")
targetLabel.BackgroundTransparency = 1
targetLabel.Size = UDim2.new(1, 0, 0, 22)
targetLabel.TextXAlignment = Enum.TextXAlignment.Left
targetLabel.Text = "🎯 Target: -"
targetLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
targetLabel.TextSize = 14
targetLabel.Font = Enum.Font.GothamMedium
targetLabel.Parent = centerTop
local reasonBox = Instance.new("TextBox")
reasonBox.Size = UDim2.new(1, 0, 0, 30)
reasonBox.PlaceholderText = "Reason (untuk Kick)"
reasonBox.Text = ""
reasonBox.ClearTextOnFocus = false
reasonBox.TextSize = 14
reasonBox.Font = Enum.Font.Gotham
reasonBox.TextColor3 = Color3.fromRGB(255, 255, 255)
reasonBox.BackgroundColor3 = Color3.fromRGB(30, 34, 42)
reasonBox.BorderSizePixel = 0
reasonBox.Parent = centerTop
local rbCorner = Instance.new("UICorner")
rbCorner.CornerRadius = UDim.new(0, 8)
rbCorner.Parent = reasonBox

local features = Instance.new("Frame")
features.BackgroundTransparency = 1
features.Parent = centerCol
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
    -- Fitur: teks tombol tetap hitam/gelap sesuai permintaan
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

-- Right: Chat
local rightCol = Instance.new("Frame")
rightCol.BackgroundTransparency = 1
rightCol.Parent = columns
local rightTitle = Instance.new("TextLabel")
rightTitle.BackgroundTransparency = 1
rightTitle.Size = UDim2.new(1, 0, 0, 22)
rightTitle.Text = "💬 Admin Chat"
rightTitle.TextXAlignment = Enum.TextXAlignment.Left
rightTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
rightTitle.TextSize = 14
rightTitle.Font = Enum.Font.GothamMedium
rightTitle.Parent = rightCol
local chatList = Instance.new("ScrollingFrame")
chatList.Name = "ChatList"
chatList.BackgroundColor3 = Color3.fromRGB(28, 32, 40)
chatList.BorderSizePixel = 0
chatList.ScrollBarThickness = 4
chatList.Parent = rightCol
local clCorner = Instance.new("UICorner")
clCorner.CornerRadius = UDim.new(0, 8)
clCorner.Parent = chatList
local clLayout = Instance.new("UIListLayout")
clLayout.Padding = UDim.new(0, 4)
clLayout.Parent = chatList
local function refreshChatCanvas()
    chatList.CanvasSize = UDim2.new(0, 0, 0, clLayout.AbsoluteContentSize.Y + 8)
end
clLayout:GetPropertyChangedSignal("AbsoluteContentSize"):Connect(refreshChatCanvas)
local chatInput = Instance.new("TextBox")
chatInput.Size = UDim2.new(1, 0, 0, 30)
chatInput.Text = ""
chatInput.PlaceholderText = "Ketik dan Enter untuk kirim"
chatInput.TextSize = 14
chatInput.Font = Enum.Font.Gotham
chatInput.TextColor3 = Color3.fromRGB(255, 255, 255)
chatInput.BackgroundColor3 = Color3.fromRGB(30, 34, 42)
chatInput.BorderSizePixel = 0
chatInput.Parent = rightCol
local ciCorner = Instance.new("UICorner")
ciCorner.CornerRadius = UDim.new(0, 8)
ciCorner.Parent = chatInput

-- Column sizing + responsive panel
local function applyResponsive()
    local vw = workspace.CurrentCamera and workspace.CurrentCamera.ViewportSize.X or 1280
    local vh = workspace.CurrentCamera and workspace.CurrentCamera.ViewportSize.Y or 720
    if IS_MOBILE then
        local width = math.clamp(vw * 0.9, 300, 420)
        local height = math.clamp(vh * 0.8, 280, 460)
        panel.Size = UDim2.new(0, width, 0, height)
    else
        local width = math.clamp(vw * 0.6, 640, 860)
        local height = math.clamp(vh * 0.6, 420, 520)
        panel.Size = UDim2.new(0, width, 0, height)
    end

    columns.Size = UDim2.new(1, 0, 1, 0)

    -- Column widths: 28% | 44% | 28%
    leftCol.Size = UDim2.new(0.28, 0, 1, 0)
    centerCol.Size = UDim2.new(0.44, 0, 1, 0)
    rightCol.Size = UDim2.new(0.28, 0, 1, 0)

    -- Layout internals
    leftTitle.Position = UDim2.new(0, 0, 0, 0)
    playerList.Position = UDim2.new(0, 0, 0, 26)
    playerList.Size = UDim2.new(1, 0, 1, -26)

    centerTop.Size = UDim2.new(1, 0, 0, 60)
    targetLabel.Position = UDim2.new(0, 0, 0, 0)
    reasonBox.Position = UDim2.new(0, 0, 0, 26)
    features.Position = UDim2.new(0, 0, 0, 60)
    features.Size = UDim2.new(1, 0, 1, -60)

    rightTitle.Position = UDim2.new(0, 0, 0, 0)
    chatList.Position = UDim2.new(0, 0, 0, 26)
    chatInput.Position = UDim2.new(0, 0, 1, -32)
    chatInput.Size = UDim2.new(1, 0, 0, 30)
    chatList.Size = UDim2.new(1, 0, 1, -26 - 34)
end

-- Toggle panel
local panelOpen = false
local function setPanel(open: boolean)
    panel.Visible = open
    panelOpen = open
end

-- PC: F5
UserInputService.InputBegan:Connect(function(input, gpe)
    if gpe then return end
    if input.KeyCode == Enum.KeyCode.F5 and not IS_MOBILE and hasAdmin then
        if not debounce("toggle", 0.25) then return end
        setPanel(not panelOpen)
    end
end)

-- Mobile: icon
openBtn.MouseButton1Click:Connect(function()
    if not debounce("toggle", 0.25) then return end
    setPanel(not panelOpen)
end)

-- Buttons (center)
local btnSpectate = makeBtn("👁️ Spectate", Color3.fromRGB(255, 230, 120))
btnSpectate.Parent = features
-- Fly removed
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
local btnHeal = makeBtn("💖 Heal", Color3.fromRGB(210, 255, 225))
btnHeal.Parent = features
local btnGod = makeBtn("🛡️ God", Color3.fromRGB(225, 210, 255))
btnGod.Parent = features
local btnUngod = makeBtn("🛡️ Off", Color3.fromRGB(230, 230, 240))
btnUngod.Parent = features
local btnGive = makeBtn("🎛️ Give Admin", Color3.fromRGB(210, 255, 210))
btnGive.Parent = features
local btnRevoke = makeBtn("🗑️ Revoke Admin", Color3.fromRGB(255, 210, 210))
btnRevoke.Parent = features
btnGive.Visible = false
btnRevoke.Visible = false

-- Player list row
local function buildPlayerRow(item)
    local row = Instance.new("TextButton")
    row.Size = UDim2.new(1, -6, 0, 26)
    row.BackgroundColor3 = Color3.fromRGB(34, 38, 46)
    row.BorderSizePixel = 0
    row.TextXAlignment = Enum.TextXAlignment.Left
    local label = item.display
    if item.label == "ADMIN" then label = label .. "  (ADMIN)" end
    if item.label == "HELPER" then label = label .. "  (HELPER)" end
    row.Text = "  " .. label
    row.TextColor3 = Color3.fromRGB(255, 255, 255)
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
        if selectedRow and selectedRow.Parent then
            selectedRow.TextColor3 = Color3.fromRGB(255, 255, 255)
        end
        selectedRow = row
        selectedName = item.name
        targetLabel.Text = "🎯 Target: " .. item.display
        row.TextColor3 = Color3.fromRGB(100, 255, 170)
        notify("Selected: " .. item.display)
    end)
    return row
end

local function refreshPlayerList(list)
    for _, child in ipairs(playerList:GetChildren()) do
        if child:IsA("TextButton") then child:Destroy() end
    end
    for _, item in ipairs(list or {}) do
        local row = buildPlayerRow(item)
        row.Parent = playerList
    end
    refreshPLCanvas()
end

-- Chat
local function addChatLine(from: string, msg: string)
    local line = Instance.new("TextLabel")
    line.BackgroundTransparency = 1
    line.TextXAlignment = Enum.TextXAlignment.Left
    line.Size = UDim2.new(1, -6, 0, 18)
    line.Text = string.format("%s: %s", from, msg)
    line.TextColor3 = Color3.fromRGB(255, 255, 255)
    line.TextSize = 13
    line.Font = Enum.Font.Gotham
    line.Parent = chatList
    refreshChatCanvas()
end

chatInput.FocusLost:Connect(function(enter)
    if not enter then return end
    if not debounce("chat", 0.25) then return end
    local txt = chatInput.Text
    if txt ~= "" and hasAdmin and REMOTE then
        REMOTE:FireServer({ t = "chat", msg = txt })
        chatInput.Text = ""
    end
end)

-- Fly removed

-- Spectate (kamera)
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

-- Quick local invis (server tetap atur global)
local function quickLocalInvis(on: boolean)
    local char = LOCAL_PLAYER.Character
    if not char then return end
    for _, inst in ipairs(char:GetDescendants()) do
        if inst:IsA("BasePart") or inst:IsA("Decal") then
            inst.LocalTransparencyModifier = on and 1 or 0
        end
    end
end

-- Buttons wiring
btnSpectate.MouseButton1Click:Connect(function()
    if not debounce("spectate", 0.3) then return end
    if not selectedName then notify("Pilih target dulu"); return end
    if REMOTE then REMOTE:FireServer({ t = "cmd", cmd = "spectate", target = selectedName }) end
end)

-- Fly removed

btnInvis.MouseButton1Click:Connect(function()
    if not debounce("invis", 0.3) then return end
    local char = LOCAL_PLAYER.Character
    local head = char and char:FindFirstChild("Head")
    local hintInvisible = head and (head :: any).LocalTransparencyModifier == 1
    quickLocalInvis(not hintInvisible)
    if REMOTE then
        if hintInvisible then REMOTE:FireServer({ t = "cmd", cmd = "vis" }) else REMOTE:FireServer({ t = "cmd", cmd = "invis" }) end
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

btnHeal.MouseButton1Click:Connect(function()
    if not debounce("heal", 0.4) then return end
    if REMOTE then REMOTE:FireServer({ t = "cmd", cmd = "heal", target = selectedName }) end
end)

btnGod.MouseButton1Click:Connect(function()
    if not debounce("god", 0.5) then return end
    if REMOTE then REMOTE:FireServer({ t = "cmd", cmd = "god", target = selectedName }) end
end)

btnUngod.MouseButton1Click:Connect(function()
    if not debounce("ungod", 0.5) then return end
    if REMOTE then REMOTE:FireServer({ t = "cmd", cmd = "ungod", target = selectedName }) end
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

-- Remote events
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
            addChatLine(payload.from or "?", payload.msg or "")
        elseif payload.t == "notify" then
            notify(payload.msg or "")
        elseif payload.t == "ovh" then
            -- Hide/Show overhead kustom untuk user tertentu (nametag, rank role, side role, summit)
            local target = nil
            for _, p in ipairs(Players:GetPlayers()) do
                if p.UserId == payload.userId then target = p break end
            end
            if target and target.Character then
                for _, inst in ipairs(target.Character:GetDescendants()) do
                    if inst:IsA("BillboardGui") or inst:IsA("SurfaceGui") then
                        inst.Enabled = not payload.hide
                    end
                end
            end
        elseif payload.t == "spectate" then
            if isSpectating and payload.target and selectedName and string.lower(payload.target) == string.lower(selectedName) then
                setSpectate(nil)
            else
                setSpectate(payload.target)
            end
        end
    end)
end

applyResponsive()
print("[AdminClient] Ready")

