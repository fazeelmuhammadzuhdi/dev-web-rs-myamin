-- Mobile Optimized Settings & Health UI
-- Menu kanan-atas: FPS, Grafik, Waktu, Hide UI + Health UI modern
-- Optimized untuk Mobile dengan ukuran kecil dan disable overhead darah

local RunService = game:GetService("RunService")
if not RunService:IsClient() then return end

local Players = game:GetService("Players")
local UserInputService = game:GetService("UserInputService")
local TweenService = game:GetService("TweenService")
local StarterGui = game:GetService("StarterGui")
local Lighting = game:GetService("Lighting")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local GuiService = game:GetService("GuiService")

local LOCAL_PLAYER = Players.LocalPlayer
local playerGui = LOCAL_PLAYER:WaitForChild("PlayerGui")

-- Disable default health bars dan overhead darah
pcall(function()
	StarterGui:SetCoreGuiEnabled(Enum.CoreGuiType.Health, false)
	StarterGui:SetCore("HealthBarSize", 0)
	StarterGui:SetCore("HealthBarTransparency", 1)
end)

-- Disable overhead darah untuk semua player
local function disableOverheadHealth()
	for _, player in pairs(Players:GetPlayers()) do
		if player ~= LOCAL_PLAYER and player.Character then
			local humanoid = player.Character:FindFirstChildOfClass("Humanoid")
			if humanoid then
				humanoid.DisplayDistanceType = Enum.HumanoidDisplayDistanceType.None
			end
		end
	end
end

-- Monitor new players
Players.PlayerAdded:Connect(function(player)
	if player.Character then
		local humanoid = player.Character:FindFirstChildOfClass("Humanoid")
		if humanoid then
			humanoid.DisplayDistanceType = Enum.HumanoidDisplayDistanceType.None
		end
	end
	player.CharacterAdded:Connect(function(character)
		local humanoid = character:WaitForChild("Humanoid")
		humanoid.DisplayDistanceType = Enum.HumanoidDisplayDistanceType.None
	end)
end)

-- Cleanup UI lama
do
	local old = playerGui:FindFirstChild("MobileSettingsHealth")
	if old then old:Destroy() end
end

-- Debounce
local function cooldown(minDelay)
	local last = 0
	return function()
		local now = os.clock()
		if now - last < minDelay then return false end
		last = now
		return true
	end
end
local canToggle = cooldown(0.2)
local canClick = cooldown(0.12)

-- Helpers
local function isTouch() return UserInputService.TouchEnabled end
local function deviceType()
	local isConsole = UserInputService.GamepadEnabled and not UserInputService.KeyboardEnabled and not UserInputService.MouseEnabled
	if isConsole then return "console" end
	if UserInputService.TouchEnabled then return "mobile" end
	return "desktop"
end
local function isMobile() return deviceType() == "mobile" end

-- Mobile-optimized sizes
local function getMobileSizes()
	if isMobile() then
		return {
			panelWidth = 200,        -- Lebih kecil untuk mobile
			buttonHeight = 28,        -- Lebih kecil
			healthSize = Vector2.new(180, 32), -- Health bar lebih kecil
			toggleSize = 30,          -- Toggle button lebih kecil
			fontSize = 12,            -- Font lebih kecil
			cornerRadius = 8,         -- Corner radius lebih kecil
		}
	else
		return {
			panelWidth = 250,
			buttonHeight = 32,
			healthSize = Vector2.new(220, 36),
			toggleSize = 34,
			fontSize = 14,
			cornerRadius = 10,
		}
	end
end

local sizes = getMobileSizes()

-- ScreenGui root
local gui = Instance.new("ScreenGui")
gui.Name = "MobileSettingsHealth"
gui.IgnoreGuiInset = true
gui.ResetOnSpawn = false
gui.ZIndexBehavior = Enum.ZIndexBehavior.Sibling
gui.DisplayOrder = 2100
gui.Parent = playerGui

-- Toast (kanan-bawah) - lebih kecil untuk mobile
local toastRoot = Instance.new("Frame")
toastRoot.Name = "ToastRoot"
toastRoot.AnchorPoint = Vector2.new(1, 1)
toastRoot.Position = UDim2.new(1, -8, 1, -8)
toastRoot.BackgroundTransparency = 1
toastRoot.Size = UDim2.new(0, isMobile() and 200 or 280, 0, 0)
toastRoot.ZIndex = 70
toastRoot.Parent = gui

local toastList = Instance.new("UIListLayout")
toastList.FillDirection = Enum.FillDirection.Vertical
toastList.HorizontalAlignment = Enum.HorizontalAlignment.Right
toastList.VerticalAlignment = Enum.VerticalAlignment.Bottom
toastList.Padding = UDim.new(0, 6)
toastList.Parent = toastRoot

local function showToast(text)
	local item = Instance.new("TextLabel")
	item.Name = "Toast"
	item.BackgroundColor3 = Color3.fromRGB(26, 28, 36)
	item.Text = text
	item.Font = Enum.Font.GothamBold
	item.TextSize = isMobile() and 11 or 13
	item.TextColor3 = Color3.fromRGB(235, 236, 245)
	item.TextWrapped = true
	item.AutomaticSize = Enum.AutomaticSize.Y
	item.Size = UDim2.new(0, isMobile() and 180 or 260, 0, 0)
	item.ZIndex = 71
	item.Parent = toastRoot
	local c = Instance.new("UICorner"); c.CornerRadius = UDim.new(0, sizes.cornerRadius); c.Parent = item
	local s = Instance.new("UIStroke"); s.Color = Color3.fromRGB(70, 72, 90); s.Thickness = 1; s.Parent = item
	local pad = Instance.new("UIPadding"); pad.PaddingLeft = UDim.new(0, 8); pad.PaddingRight = UDim.new(0, 8); pad.PaddingTop = UDim.new(0, 6); pad.PaddingBottom = UDim.new(0, 6); pad.Parent = item
	item.BackgroundTransparency = 1; item.TextTransparency = 1
	TweenService:Create(item, TweenInfo.new(0.15), { BackgroundTransparency = 0, TextTransparency = 0 }):Play()
	task.delay(2.0, function()
		local t = TweenService:Create(item, TweenInfo.new(0.15), { BackgroundTransparency = 1, TextTransparency = 1 })
		t:Play()
		t.Completed:Connect(function() if item and item.Parent then item:Destroy() end end)
	end)
end

-- Top bar (FPS kiri + Toggle kanan) - lebih kecil untuk mobile
local topBar = Instance.new("Frame")
topBar.Name = "TopBar"
topBar.AnchorPoint = Vector2.new(1, 0)
topBar.Position = UDim2.new(1, -8, 0, 8)
topBar.BackgroundTransparency = 1
topBar.AutomaticSize = Enum.AutomaticSize.XY
topBar.Size = UDim2.new(0, 0, 0, 0)
topBar.ZIndex = 50
topBar.Parent = gui

local lay = Instance.new("UIListLayout")
lay.FillDirection = Enum.FillDirection.Horizontal
lay.HorizontalAlignment = Enum.HorizontalAlignment.Right
lay.VerticalAlignment = Enum.VerticalAlignment.Top
lay.Padding = UDim.new(0, 6)
lay.Parent = topBar

-- FPS label (default hidden) - lebih kecil untuk mobile
local fpsLabel = Instance.new("TextLabel")
fpsLabel.Name = "FPS"
fpsLabel.BackgroundColor3 = Color3.fromRGB(26, 28, 36)
fpsLabel.Text = "FPS: 60"
fpsLabel.Font = Enum.Font.GothamBold
fpsLabel.TextSize = isMobile() and 11 or 13
fpsLabel.TextColor3 = Color3.fromRGB(235, 236, 245)
fpsLabel.AutomaticSize = Enum.AutomaticSize.X
fpsLabel.Size = UDim2.new(0, 0, 0, sizes.toggleSize)
fpsLabel.Visible = false
fpsLabel.ZIndex = 50
fpsLabel.Parent = topBar
do
	local c = Instance.new("UICorner"); c.CornerRadius = UDim.new(0, sizes.cornerRadius); c.Parent = fpsLabel
	local s = Instance.new("UIStroke"); s.Color = Color3.fromRGB(70, 72, 90); s.Thickness = 1; s.Parent = fpsLabel
	local pad = Instance.new("UIPadding"); pad.PaddingLeft = UDim.new(0, 8); pad.PaddingRight = UDim.new(0, 8); pad.Parent = fpsLabel
end

-- Toggle icon (emoji) - lebih kecil untuk mobile
local toggleBtn = Instance.new("TextButton")
toggleBtn.Name = "Toggle"
toggleBtn.Text = ""
toggleBtn.Font = Enum.Font.GothamBold
toggleBtn.TextSize = isMobile() and 14 or 16
toggleBtn.TextColor3 = Color3.fromRGB(240, 241, 245)
toggleBtn.BackgroundColor3 = Color3.fromRGB(26, 28, 36)
toggleBtn.AutoButtonColor = true
toggleBtn.Size = UDim2.new(0, sizes.toggleSize, 0, sizes.toggleSize)
toggleBtn.ZIndex = 50
toggleBtn.Parent = topBar
do
	local c = Instance.new("UICorner"); c.CornerRadius = UDim.new(0, sizes.cornerRadius); c.Parent = toggleBtn
	local s = Instance.new("UIStroke"); s.Color = Color3.fromRGB(70, 72, 90); s.Thickness = 1; s.Parent = toggleBtn
end

local emoteBadge = Instance.new("TextLabel")
emoteBadge.Name = "Emote"
emoteBadge.BackgroundTransparency = 1
emoteBadge.Text = "🙂"
emoteBadge.Font = Enum.Font.GothamBold
emoteBadge.TextScaled = true
emoteBadge.TextColor3 = Color3.fromRGB(255, 255, 255)
emoteBadge.AnchorPoint = Vector2.new(0.5, 0.5)
emoteBadge.Position = UDim2.new(0.5, 0, 0.5, 0)
emoteBadge.Size = UDim2.new(0, math.floor(sizes.toggleSize * 0.7), 0, math.floor(sizes.toggleSize * 0.7))
emoteBadge.ZIndex = 51
emoteBadge.Parent = toggleBtn

-- Scrim
local scrim = Instance.new("TextButton")
scrim.Name = "Scrim"
scrim.Text = ""
scrim.AutoButtonColor = false
scrim.BackgroundColor3 = Color3.fromRGB(0, 0, 0)
scrim.BackgroundTransparency = 1
scrim.Visible = false
scrim.Active = true
scrim.ZIndex = 40
scrim.Size = UDim2.new(1, 0, 1, 0)
scrim.Parent = gui

-- Panel - lebih kecil untuk mobile
local panel = Instance.new("Frame")
panel.Name = "Panel"
panel.BackgroundColor3 = Color3.fromRGB(20, 22, 30)
panel.AnchorPoint = Vector2.new(1, 0)
panel.Position = UDim2.new(1, -8, 0, (sizes.toggleSize + 12))
panel.Size = UDim2.new(0, sizes.panelWidth, 0, 0)
panel.Visible = false
panel.ClipsDescendants = true
panel.ZIndex = 45
panel.Parent = gui
do
	local c = Instance.new("UICorner"); c.CornerRadius = UDim.new(0, sizes.cornerRadius); c.Parent = panel
	local s = Instance.new("UIStroke"); s.Color = Color3.fromRGB(70, 72, 90); s.Thickness = 1; s.Parent = panel
end

local container = Instance.new("Frame")
container.Name = "Container"
container.BackgroundTransparency = 1
container.Size = UDim2.new(1, -12, 1, -12)
container.Position = UDim2.new(0, 6, 0, 6)
container.ZIndex = 46
container.Parent = panel

local list = Instance.new("UIListLayout")
list.Padding = UDim.new(0, 4)
list.SortOrder = Enum.SortOrder.LayoutOrder
list.Parent = container

local function makeRow(text)
	local btn = Instance.new("TextButton")
	btn.Name = "Row_" .. text
	btn.Text = text
	btn.Font = Enum.Font.Gotham
	btn.TextSize = isMobile() and 12 or 14
	btn.TextColor3 = Color3.fromRGB(230, 232, 240)
	btn.BackgroundColor3 = Color3.fromRGB(28, 30, 40)
	btn.AutoButtonColor = true
	btn.Size = UDim2.new(1, 0, 0, sizes.buttonHeight)
	btn.ZIndex = 46
	btn.Parent = container
	local c = Instance.new("UICorner"); c.CornerRadius = UDim.new(0, sizes.cornerRadius); c.Parent = btn
	local s = Instance.new("UIStroke"); s.Color = Color3.fromRGB(58, 60, 74); s.Thickness = 1; s.Parent = btn
	return btn
end

local function makeSubHeader(text)
	local lbl = Instance.new("TextLabel")
	lbl.Name = "SubHeader_" .. text
	lbl.BackgroundTransparency = 1
	lbl.Text = text
	lbl.TextXAlignment = Enum.TextXAlignment.Left
	lbl.Font = Enum.Font.GothamBold
	lbl.TextSize = isMobile() and 13 or 15
	lbl.TextColor3 = Color3.fromRGB(235, 236, 245)
	lbl.Size = UDim2.new(1, 0, 0, sizes.buttonHeight)
	lbl.ZIndex = 47
	lbl.Parent = container
	return lbl
end

-- Settings state
local settings = {
	backup = nil,
	lowActive = false,
	hideUi = false,
	fpsVisible = false,
}

-- Snapshot/restore Lighting
local postEffectStates = nil
local function snapshotDefaults()
	if settings.backup then return end
	postEffectStates = {}
	for _, child in ipairs(Lighting:GetChildren()) do
		if child:IsA("PostEffect") then postEffectStates[child] = child.Enabled end
	end
	settings.backup = {
		GlobalShadows = Lighting.GlobalShadows,
		Brightness = Lighting.Brightness,
		FogEnd = Lighting.FogEnd,
		ClockTime = Lighting.ClockTime,
	}
end

local function applyHigh()
	snapshotDefaults()
	local b = settings.backup
	Lighting.GlobalShadows = b.GlobalShadows
	Lighting.Brightness = b.Brightness
	Lighting.FogEnd = b.FogEnd
	for inst, enabled in pairs(postEffectStates or {}) do
		if inst and inst.Parent == Lighting then inst.Enabled = enabled end
	end
	settings.lowActive = false
	showToast("Grafik: High")
end

local function applyLow()
	snapshotDefaults()
	Lighting.GlobalShadows = false
	Lighting.Brightness = 1
	Lighting.FogEnd = 100
	for _, child in ipairs(Lighting:GetChildren()) do
		if child:IsA("PostEffect") then child.Enabled = false end
	end
	settings.lowActive = true
	showToast("Grafik: Low (Boost FPS)")
end

local function setPagi()	Lighting.ClockTime = 7.0;	showToast("Waktu: Pagi")	end
local function setSiang()	Lighting.ClockTime = 12.0;	showToast("Waktu: Siang")	end
local function setSore()	Lighting.ClockTime = 17.0;	showToast("Waktu: Sore")	end
local function setMalam()	Lighting.ClockTime = 0.0;	showToast("Waktu: Malam")	end

-- Hide UI
local hiddenGuis = {}
local function setHideUi(on)
	settings.hideUi = on
	pcall(function() StarterGui:SetCoreGuiEnabled(Enum.CoreGuiType.All, not on) end)
	if on then
		hiddenGuis = {}
		for _, sg in ipairs(playerGui:GetChildren()) do
			if sg ~= gui and sg:IsA("ScreenGui") and sg.Enabled ~= false then
				hiddenGuis[sg] = true
				sg.Enabled = false
			end
		end
	else
		for sg, _ in pairs(hiddenGuis) do
			if sg.Parent then sg.Enabled = true end
		end
		hiddenGuis = {}
	end
	showToast(on and "Hide UI: ON" or "Hide UI: OFF")
end

local function setFpsVisible(on)
	settings.fpsVisible = on
	fpsLabel.Visible = on
	showToast(on and "FPS: ON" or "FPS: OFF")
end

-- Settings rows
do
	local mainHeader = Instance.new("TextLabel")
	mainHeader.Name = "Header"
	mainHeader.BackgroundTransparency = 1
	mainHeader.Size = UDim2.new(1, 0, 0, sizes.buttonHeight)
	mainHeader.Font = Enum.Font.GothamBold
	mainHeader.TextSize = isMobile() and 13 or 15
	mainHeader.TextXAlignment = Enum.TextXAlignment.Left
	mainHeader.TextColor3 = Color3.fromRGB(235, 236, 245)
	mainHeader.Text = "Settings"
	mainHeader.ZIndex = 47
	mainHeader.Parent = container

	local rowHigh	= makeRow("Grafik: High")
	local rowLow	= makeRow("Grafik: Low")
	local rowPagi	= makeRow("Waktu: Pagi")
	local rowSiang	= makeRow("Waktu: Siang")
	local rowSore	= makeRow("Waktu: Sore")
	local rowMalam	= makeRow("Waktu: Malam")
	local rowHideUi	= makeRow("Hide UI: ON/OFF")
	local rowFps	= makeRow("FPS: Show/Hide")

	rowHigh.MouseButton1Click:Connect(function() if not canClick() then return end applyHigh() end)
	rowLow.MouseButton1Click:Connect(function() if not canClick() then return end applyLow() end)
	rowPagi.MouseButton1Click:Connect(function() if not canClick() then return end setPagi() end)
	rowSiang.MouseButton1Click:Connect(function() if not canClick() then return end setSiang() end)
	rowSore.MouseButton1Click:Connect(function() if not canClick() then return end setSore() end)
	rowMalam.MouseButton1Click:Connect(function() if not canClick() then return end setMalam() end)
	rowHideUi.MouseButton1Click:Connect(function() if not canClick() then return end setHideUi(not settings.hideUi) end)
	rowFps.MouseButton1Click:Connect(function() if not canClick() then return end setFpsVisible(not settings.fpsVisible) end)
end

-- =========================
-- HEALTH UI SECTION (MOBILE OPTIMIZED)
-- =========================

-- Mobile-optimized health presets
local Presets = {
	TopLeft = { anchor = Vector2.new(0, 0), pos = function() return UDim2.new(0, 8, 0, 8) end },
	TopCenter = { anchor = Vector2.new(0.5, 0), pos = function() return UDim2.new(0.5, 0, 0, 8) end },
	TopRight = { anchor = Vector2.new(1, 0), pos = function() return UDim2.new(1, -8, 0, 8) end },
	CenterLeft = { anchor = Vector2.new(0, 0.5), pos = function() return UDim2.new(0, 8, 0.5, 0) end },
	Center = { anchor = Vector2.new(0.5, 0.5), pos = function() return UDim2.new(0.5, 0, 0.5, 0) end },
	CenterRight = { anchor = Vector2.new(1, 0.5), pos = function() return UDim2.new(1, -8, 0.5, 0) end },
	BottomLeft = {
		anchor = Vector2.new(0, 1),
		pos = function()
			local inset = GuiService:GetGuiInset().Y
			return UDim2.new(0, 8, 1, -(8 + inset))
		end
	},
	BottomCenter = {
		anchor = Vector2.new(0.5, 1),
		pos = function()
			local inset = GuiService:GetGuiInset().Y
			return UDim2.new(0.5, 0, 1, -(8 + inset))
		end
	},
	BottomRight = {
		anchor = Vector2.new(1, 1),
		pos = function()
			local inset = GuiService:GetGuiInset().Y
			return UDim2.new(1, -8, 1, -(8 + inset))
		end
	},
	BottomCenterAboveInventory = {
		anchor = Vector2.new(0.5, 1),
		pos = function()
			local inset = GuiService:GetGuiInset().Y
			local gap = isMobile() and 60 or 72
			return UDim2.new(0.5, 0, 1, -(gap + inset + 4))
		end
	},
}

local presetOrder = {
	"BottomCenterAboveInventory","BottomCenter","BottomLeft","BottomRight",
	"Center","CenterLeft","CenterRight","TopCenter","TopLeft","TopRight",
}

-- Health state (default: BottomLeft)
local healthState = {
	modelIndex = 2,      -- 1 Minimal, 2 Pill, 3 Segmented, 4 Circle, 5 Kotak
	scale = isMobile() and 0.8 or 1.0,  -- Lebih kecil untuk mobile
	transparency = 0.2,
	hidden = false,
	positionPreset = "BottomLeft",
}

-- Health root
local healthRoot, activeModel, humanoid
local connHealth, connMax, connRespawn

local function destroyActiveModel()
	if activeModel and activeModel.destroy then activeModel.destroy() end
	activeModel = nil
end

-- Color helpers
local colRed = Color3.fromRGB(240, 71, 71)
local colYellow = Color3.fromRGB(255, 197, 61)
local colGreen = Color3.fromRGB(52, 211, 153)
local function hpColorByPercent(p)
	if p <= 0.5 then
		return Color3.new(
			colRed.R + (colYellow.R - colRed.R) * (p / 0.5),
			colRed.G + (colYellow.G - colRed.G) * (p / 0.5),
			colRed.B + (colYellow.B - colRed.B) * (p / 0.5)
		)
	end
	return Color3.new(
		colYellow.R + (colGreen.R - colYellow.R) * ((p - 0.5) / 0.5),
		colYellow.G + (colGreen.G - colYellow.G) * ((p - 0.5) / 0.5),
		colYellow.B + (colGreen.B - colYellow.B) * ((p - 0.5) / 0.5)
	)
end

-- Mobile-optimized Pill model (default)
local function createModel_Pill(parent)
	local container = Instance.new("Frame")
	container.Name = "HP_Pill"
	container.BackgroundColor3 = Color3.fromRGB(20, 20, 24)
	container.BackgroundTransparency = healthState.transparency
	container.Size = UDim2.new(1, 0, 1, 0)
	container.Parent = parent
	local corner = Instance.new("UICorner"); corner.CornerRadius = UDim.new(0, sizes.cornerRadius); corner.Parent = container

	local pad = Instance.new("UIPadding")
	pad.PaddingLeft = UDim.new(0, 6); pad.PaddingRight = UDim.new(0, 6); pad.PaddingTop = UDim.new(0, 4); pad.PaddingBottom = UDim.new(0, 4)
	pad.Parent = container

	local layout = Instance.new("UIListLayout")
	layout.FillDirection = Enum.FillDirection.Horizontal
	layout.VerticalAlignment = Enum.VerticalAlignment.Center
	layout.HorizontalAlignment = Enum.HorizontalAlignment.Left
	layout.Padding = UDim.new(0, 6)
	layout.Parent = container

	local icon = Instance.new("ImageLabel")
	icon.BackgroundTransparency = 1
	icon.Size = UDim2.new(0, isMobile() and 14 or 16, 0, isMobile() and 14 or 16)
	icon.Image = "rbxassetid://10769511192"
	icon.ImageColor3 = Color3.fromRGB(255, 255, 255)
	icon.Parent = container

	local right = Instance.new("Frame")
	right.BackgroundTransparency = 1
	right.Size = UDim2.new(1, -20, 1, 0)
	right.Parent = container

	local rightLayout = Instance.new("UIListLayout")
	rightLayout.FillDirection = Enum.FillDirection.Vertical
	rightLayout.VerticalAlignment = Enum.VerticalAlignment.Center
	rightLayout.Parent = right

	local bar = Instance.new("Frame")
	bar.BackgroundColor3 = Color3.fromRGB(35, 35, 42)
	bar.BackgroundTransparency = math.clamp(0.2 + healthState.transparency * 0.8, 0, 1)
	bar.Size = UDim2.new(1, 0, 0, isMobile() and 6 or 8)
	bar.Parent = right
	local bC = Instance.new("UICorner"); bC.CornerRadius = UDim.new(0, sizes.cornerRadius); bC.Parent = bar

	local fill = Instance.new("Frame")
	fill.BackgroundColor3 = colGreen
	fill.BackgroundTransparency = math.clamp(healthState.transparency * 0.6, 0, 0.9)
	fill.Size = UDim2.new(1, 0, 1, 0)
	fill.Parent = bar
	local fC = Instance.new("UICorner"); fC.CornerRadius = UDim.new(0, sizes.cornerRadius); fC.Parent = fill

	local text = Instance.new("TextLabel")
	text.BackgroundTransparency = 1
	text.TextXAlignment = Enum.TextXAlignment.Left
	text.Size = UDim2.new(1, 0, 0, isMobile() and 10 or 12)
	text.Font = Enum.Font.GothamSemibold
	text.TextSize = isMobile() and 10 or 12
	text.TextColor3 = Color3.fromRGB(225, 225, 225)
	text.Text = "HP 100/100"
	text.Parent = right

	local last = -1
	local function update(p, health, maxHealth)
		p = math.clamp(p, 0, 1)
		if math.abs(p - last) < 0.001 then return end
		last = p
		text.Text = ("HP %d/%d"):format(math.floor((health or p * 100) + 0.5), math.floor((maxHealth or 100) + 0.5))
		fill.BackgroundColor3 = hpColorByPercent(p)
		TweenService:Create(fill, TweenInfo.new(0.12, Enum.EasingStyle.Quad, Enum.EasingDirection.Out), { Size = UDim2.new(p, 0, 1, 0) }):Play()
	end
	local function setTransparency(t)
		container.BackgroundTransparency = t
		bar.BackgroundTransparency = math.clamp(0.2 + t * 0.8, 0, 1)
		fill.BackgroundTransparency = math.clamp(t * 0.6, 0, 0.9)
	end
	return { update = update, setTransparency = setTransparency, destroy = function() container:Destroy() end }
end

local function buildModel(parent)
	destroyActiveModel()
	activeModel = createModel_Pill(parent) -- Hanya gunakan Pill model untuk mobile
	if activeModel and activeModel.setTransparency then
		activeModel.setTransparency(healthState.transparency)
	end
end

local function applyHealthLayout()
	if not healthRoot then return end
	local base = sizes.healthSize
	local scale = math.clamp(healthState.scale, 0.6, 1.2) -- Range lebih kecil untuk mobile
	local w = math.floor(base.X * scale + 0.5)
	local h = math.floor(base.Y * scale + 0.5)
	healthRoot.Size = UDim2.new(0, w, 0, h)
	local preset = Presets[healthState.positionPreset] or Presets.BottomLeft
	healthRoot.AnchorPoint = preset.anchor
	healthRoot.Position = preset.pos()
end

local function buildHealthRoot()
	if healthRoot then healthRoot:Destroy() end
	healthRoot = Instance.new("Frame")
	healthRoot.Name = "HealthRoot"
	healthRoot.BackgroundTransparency = 1
	healthRoot.Size = UDim2.new(0, sizes.healthSize.X, 0, sizes.healthSize.Y)
	healthRoot.ZIndex = 10
	healthRoot.Parent = gui
	buildModel(healthRoot)
	applyHealthLayout()
	healthRoot.Visible = not healthState.hidden
end

-- Health binding
local lastPercentShown = -1
local function pushHealth()
	if not humanoid or not activeModel then return end
	local maxH = math.max(1, humanoid.MaxHealth)
	local curH = math.clamp(humanoid.Health, 0, maxH)
	local p = curH / maxH
	if math.abs(p - lastPercentShown) < 0.001 then return end
	lastPercentShown = p
	activeModel.update(p, curH, maxH)
end

local function bindHumanoid(h)
	if connHealth then connHealth:Disconnect() end
	if connMax then connMax:Disconnect() end
	humanoid = h
	connHealth = humanoid.HealthChanged:Connect(pushHealth)
	connMax = humanoid:GetPropertyChangedSignal("MaxHealth"):Connect(pushHealth)
	pushHealth()
end

local function setupCharacter()
	local char = LOCAL_PLAYER.Character or LOCAL_PLAYER.CharacterAdded:Wait()
	local h = char:FindFirstChildOfClass("Humanoid") or char:WaitForChild("Humanoid")
	bindHumanoid(h)
	if connRespawn then connRespawn:Disconnect() end
	connRespawn = char.ChildAdded:Connect(function(c)
		if c:IsA("Humanoid") then bindHumanoid(c) end
	end)
end

-- Health Menu - lebih sederhana untuk mobile
makeSubHeader("Health UI")
local rowPos = makeRow("Posisi: Bawah Kiri")
local rowHideHP = makeRow("Hide Health: OFF")

-- Scale mini controls - lebih kecil untuk mobile
local scaleRow = Instance.new("Frame")
scaleRow.Name = "HP_ScaleRow"
scaleRow.BackgroundTransparency = 1
scaleRow.Size = UDim2.new(1, 0, 0, sizes.buttonHeight)
scaleRow.ZIndex = 46
scaleRow.Parent = container

local scaleLayout = Instance.new("UIListLayout")
scaleLayout.FillDirection = Enum.FillDirection.Horizontal
scaleLayout.Padding = UDim.new(0, 4)
scaleLayout.Parent = scaleRow

local function miniBtn(text)
	local b = Instance.new("TextButton")
	b.Text = text
	b.Font = Enum.Font.GothamSemibold
	b.TextSize = isMobile() and 10 or 12
	b.TextColor3 = Color3.fromRGB(230, 232, 240)
	b.BackgroundColor3 = Color3.fromRGB(28, 30, 40)
	b.AutoButtonColor = true
	b.Size = UDim2.new(0, isMobile() and 24 or 28, 1, 0)
	local c = Instance.new("UICorner"); c.CornerRadius = UDim.new(0, sizes.cornerRadius); c.Parent = b
	local s = Instance.new("UIStroke"); s.Color = Color3.fromRGB(58, 60, 74); s.Thickness = 1; s.Parent = b
	b.Parent = scaleRow
	return b
end

local scaleMinus = miniBtn("-")
local scaleLabel = Instance.new("TextButton")
scaleLabel.Text = "Scale: 80%"
scaleLabel.Font = Enum.Font.GothamSemibold
scaleLabel.TextSize = isMobile() and 10 or 12
scaleLabel.TextColor3 = Color3.fromRGB(230, 232, 240)
scaleLabel.BackgroundColor3 = Color3.fromRGB(24, 26, 34)
scaleLabel.AutoButtonColor = false
scaleLabel.Size = UDim2.new(1, -56, 1, 0)
do
	local c = Instance.new("UICorner"); c.CornerRadius = UDim.new(0, sizes.cornerRadius); c.Parent = scaleLabel
	local s = Instance.new("UIStroke"); s.Color = Color3.fromRGB(58, 60, 74); s.Thickness = 1; s.Parent = scaleLabel
end
scaleLabel.Parent = scaleRow
local scalePlus = miniBtn("+")

-- Handlers Health
local function presetLabel(key)
	if key == "BottomCenterAboveInventory" then return "Atas Inventory" end
	if key == "BottomCenter" then return "Bawah Tengah" end
	if key == "BottomLeft" then return "Bawah Kiri" end
	if key == "BottomRight" then return "Bawah Kanan" end
	if key == "Center" then return "Tengah" end
	if key == "CenterLeft" then return "Tengah Kiri" end
	if key == "CenterRight" then return "Tengah Kanan" end
	if key == "TopCenter" then return "Atas Tengah" end
	if key == "TopLeft" then return "Atas Kiri" end
	if key == "TopRight" then return "Atas Kanan" end
	return key
end

local function refreshPosLabel() rowPos.Text = "Posisi: " .. presetLabel(healthState.positionPreset) end
local function refreshScaleLabel() scaleLabel.Text = ("Scale: %d%%"):format(math.floor(healthState.scale * 100 + 0.5)) end
local function refreshHideHP() rowHideHP.Text = "Hide Health: " .. (healthState.hidden and "ON" or "OFF") end

rowPos.MouseButton1Click:Connect(function()
	if not canClick() then return end
	local idx = 1
	for i, k in ipairs(presetOrder) do
		if k == healthState.positionPreset then
			idx = i
			break
		end
	end
	idx = idx % #presetOrder + 1
	healthState.positionPreset = presetOrder[idx]
	refreshPosLabel()
	applyHealthLayout()
end)

scaleMinus.MouseButton1Click:Connect(function()
	if not canClick() then return end
	healthState.scale = math.max(0.6, healthState.scale - 0.05)
	refreshScaleLabel()
	applyHealthLayout()
end)
scalePlus.MouseButton1Click:Connect(function()
	if not canClick() then return end
	healthState.scale = math.min(1.2, healthState.scale + 0.05)
	refreshScaleLabel()
	applyHealthLayout()
end)

rowHideHP.MouseButton1Click:Connect(function()
	if not canClick() then return end
	healthState.hidden = not healthState.hidden
	refreshHideHP()
	if healthRoot then healthRoot.Visible = not healthState.hidden end
end)

-- Init Health UI + labels
buildHealthRoot()
refreshPosLabel(); refreshScaleLabel(); refreshHideHP()

-- Open/Close menu
local function desiredHeight()
	local rows = 0
	for _, child in ipairs(container:GetChildren()) do
		if child:IsA("GuiObject") then rows += 1 end
	end
	return (rows * sizes.buttonHeight) + ((rows - 1) * 4) + 12 + 4
end

local isOpen, anim = false, false
local function openMenu()
	if isOpen or anim then return end
	isOpen, anim = true, true
	panel.Visible = true
	scrim.Visible = true
	scrim.BackgroundTransparency = 1
	emoteBadge.Text = "😄"
	TweenService:Create(scrim, TweenInfo.new(0.15), { BackgroundTransparency = 0.3 }):Play()
	panel.Size = UDim2.new(0, sizes.panelWidth, 0, 0)
	local h = desiredHeight()
	local t = TweenService:Create(panel, TweenInfo.new(0.15, Enum.EasingStyle.Quad, Enum.EasingDirection.Out), { Size = UDim2.new(0, sizes.panelWidth, 0, h) })
	t:Play()
	t.Completed:Connect(function() anim = false end)
end

local function closeMenu()
	if (not isOpen) or anim then return end
	isOpen, anim = false, true
	emoteBadge.Text = "🙂"
	local t1 = TweenService:Create(panel, TweenInfo.new(0.14, Enum.EasingStyle.Quad, Enum.EasingDirection.In), { Size = UDim2.new(0, sizes.panelWidth, 0, 0) })
	local t2 = TweenService:Create(scrim, TweenInfo.new(0.14), { BackgroundTransparency = 1 })
	t1:Play(); t2:Play()
	t1.Completed:Connect(function()
		panel.Visible = false
		scrim.Visible = false
		anim = false
	end)
end

local function toggleMenu()
	if not canToggle() then return end
	if isOpen then closeMenu() else openMenu() end
end
toggleBtn.MouseButton1Click:Connect(toggleMenu)
scrim.MouseButton1Click:Connect(function() if not canToggle() then return end closeMenu() end)
UserInputService.InputBegan:Connect(function(input, gp)
	if gp then return end
	if input.KeyCode == Enum.KeyCode.Escape then closeMenu() end
end)

-- FPS updater
do
	local accum, count, elapsed = 0, 0, 0
	RunService.RenderStepped:Connect(function(dt)
		accum += dt; count += 1; elapsed += dt
		if elapsed >= 0.25 then
			local fps = math.clamp(math.floor(count / accum + 0.5), 1, 999)
			if settings.fpsVisible then
				fpsLabel.Text = ("FPS: %d"):format(fps)
			end
			accum, count, elapsed = 0, 0, 0
		end
	end)
end

-- Defaults
applyHigh()
setFpsVisible(false)

-- Health bind
setupCharacter()
LOCAL_PLAYER.CharacterAdded:Connect(function()
	task.wait(0.05)
	setupCharacter()
end)

-- Disable overhead health untuk semua player
disableOverheadHealth()

print("📱 MOBILE OPTIMIZED SETTINGS & HEALTH UI READY!")
print("✅ Ukuran menu dan health bar dioptimalkan untuk mobile")
print("✅ Default overhead darah player lain dinonaktifkan")
print("✅ Health bar lebih kecil dan responsif")
print("✅ Font dan button size disesuaikan untuk mobile")