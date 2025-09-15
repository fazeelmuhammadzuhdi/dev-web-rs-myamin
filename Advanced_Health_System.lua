-- Advanced Health System untuk Roblox
-- Memindahkan default HP ke kiri pojok bawah dengan fitur advanced
-- Model baru yang simple, responsive, dan dioptimalkan untuk semua device

local RunService = game:GetService("RunService")
if not RunService:IsClient() then return end

local Players = game:GetService("Players")
local UserInputService = game:GetService("UserInputService")
local TweenService = game:GetService("TweenService")
local StarterGui = game:GetService("StarterGui")
local GuiService = game:GetService("GuiService")
local SoundService = game:GetService("SoundService")

local LOCAL_PLAYER = Players.LocalPlayer
local playerGui = LOCAL_PLAYER:WaitForChild("PlayerGui")

-- Configuration
local CONFIG = {
	-- Position
	POSITION = "BottomLeft", -- "BottomLeft", "BottomRight", "TopLeft", "TopRight"
	
	-- Animation
	ANIMATION_SPEED = 0.3,
	PULSE_ON_LOW_HEALTH = true,
	LOW_HEALTH_THRESHOLD = 0.25,
	
	-- Colors
	COLORS = {
		FULL = Color3.fromRGB(52, 211, 153),    -- Green
		MEDIUM = Color3.fromRGB(255, 197, 61),  -- Yellow
		LOW = Color3.fromRGB(240, 71, 71),      -- Red
		BACKGROUND = Color3.fromRGB(20, 20, 24),
		STROKE = Color3.fromRGB(60, 60, 70),
		TEXT = Color3.fromRGB(255, 255, 255)
	},
	
	-- Sounds (optional)
	ENABLE_SOUNDS = false,
	LOW_HEALTH_SOUND = "rbxassetid://131961136", -- Beep sound
}

-- Disable default health bar dan overhead HP
pcall(function()
	StarterGui:SetCoreGuiEnabled(Enum.CoreGuiType.Health, false)
	StarterGui:SetCore("HealthBarSize", 0)
	StarterGui:SetCore("HealthBarTransparency", 1)
end)

-- Disable overhead HP untuk semua player
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

-- Monitor new players untuk disable overhead HP
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
	local old = playerGui:FindFirstChild("AdvancedHealthSystem")
	if old then old:Destroy() end
end

-- Device Detection
local function getDeviceType()
	local isConsole = UserInputService.GamepadEnabled and not UserInputService.KeyboardEnabled and not UserInputService.MouseEnabled
	if isConsole then return "console" end
	if UserInputService.TouchEnabled then return "mobile" end
	return "desktop"
end

local function isMobile() return getDeviceType() == "mobile" end
local function isConsole() return getDeviceType() == "console" end

-- Device-specific sizes
local function getHealthSizes()
	local device = getDeviceType()
	if device == "mobile" then
		return {
			width = 140,        -- Lebih kecil untuk mobile
			height = 22,         -- Lebih kecil untuk mobile
			fontSize = 9,        -- Font lebih kecil
			iconSize = 10,       -- Icon lebih kecil
			barHeight = 3,       -- Bar lebih tipis
			cornerRadius = 5,    -- Corner radius lebih kecil
			padding = 3,         -- Padding lebih kecil
			spacing = 3,         -- Spacing lebih kecil
		}
	elseif device == "console" then
		return {
			width = 160,        -- Sedang untuk console
			height = 26,         -- Sedang untuk console
			fontSize = 10,       -- Font sedang
			iconSize = 12,       -- Icon sedang
			barHeight = 4,       -- Bar sedang
			cornerRadius = 6,    -- Corner radius sedang
			padding = 4,         -- Padding sedang
			spacing = 4,         -- Spacing sedang
		}
	else
		return {
			width = 180,        -- Normal untuk desktop
			height = 30,         -- Normal untuk desktop
			fontSize = 11,       -- Font normal
			iconSize = 14,       -- Icon normal
			barHeight = 5,       -- Bar normal
			cornerRadius = 7,    -- Corner radius normal
			padding = 5,         -- Padding normal
			spacing = 5,         -- Spacing normal
		}
	end
end

local sizes = getHealthSizes()

-- Position presets
local POSITIONS = {
	BottomLeft = function() return UDim2.new(0, sizes.padding, 1, -sizes.padding) end,
	BottomRight = function() return UDim2.new(1, -sizes.width - sizes.padding, 1, -sizes.padding) end,
	TopLeft = function() return UDim2.new(0, sizes.padding, 0, sizes.padding) end,
	TopRight = function() return UDim2.new(1, -sizes.width - sizes.padding, 0, sizes.padding) end,
}

local ANCHORS = {
	BottomLeft = Vector2.new(0, 1),
	BottomRight = Vector2.new(1, 1),
	TopLeft = Vector2.new(0, 0),
	TopRight = Vector2.new(1, 0),
}

-- ScreenGui root
local gui = Instance.new("ScreenGui")
gui.Name = "AdvancedHealthSystem"
gui.IgnoreGuiInset = true
gui.ResetOnSpawn = false
gui.ZIndexBehavior = Enum.ZIndexBehavior.Sibling
gui.DisplayOrder = 1000
gui.Parent = playerGui

-- Health Container
local healthContainer = Instance.new("Frame")
healthContainer.Name = "HealthContainer"
healthContainer.BackgroundColor3 = CONFIG.COLORS.BACKGROUND
healthContainer.BackgroundTransparency = 0.1
healthContainer.Size = UDim2.new(0, sizes.width, 0, sizes.height)
healthContainer.AnchorPoint = ANCHORS[CONFIG.POSITION]
healthContainer.Position = POSITIONS[CONFIG.POSITION]()
healthContainer.ZIndex = 10
healthContainer.Parent = gui

-- Corner radius
local corner = Instance.new("UICorner")
corner.CornerRadius = UDim.new(0, sizes.cornerRadius)
corner.Parent = healthContainer

-- Stroke
local stroke = Instance.new("UIStroke")
stroke.Color = CONFIG.COLORS.STROKE
stroke.Thickness = 1
stroke.Parent = healthContainer

-- Padding
local padding = Instance.new("UIPadding")
padding.PaddingLeft = UDim.new(0, sizes.padding)
padding.PaddingRight = UDim.new(0, sizes.padding)
padding.PaddingTop = UDim.new(0, sizes.padding)
padding.PaddingBottom = UDim.new(0, sizes.padding)
padding.Parent = healthContainer

-- Layout
local layout = Instance.new("UIListLayout")
layout.FillDirection = Enum.FillDirection.Horizontal
layout.VerticalAlignment = Enum.VerticalAlignment.Center
layout.HorizontalAlignment = Enum.HorizontalAlignment.Left
layout.Padding = UDim.new(0, sizes.spacing)
layout.Parent = healthContainer

-- Health Icon
local healthIcon = Instance.new("ImageLabel")
healthIcon.Name = "HealthIcon"
healthIcon.BackgroundTransparency = 1
healthIcon.Size = UDim2.new(0, sizes.iconSize, 0, sizes.iconSize)
healthIcon.Image = "rbxassetid://10769511192" -- Heart icon
healthIcon.ImageColor3 = CONFIG.COLORS.TEXT
healthIcon.Parent = healthContainer

-- Health Info Container
local healthInfo = Instance.new("Frame")
healthInfo.Name = "HealthInfo"
healthInfo.BackgroundTransparency = 1
healthInfo.Size = UDim2.new(1, -sizes.iconSize - sizes.spacing, 1, 0)
healthInfo.Parent = healthContainer

-- Health Info Layout
local infoLayout = Instance.new("UIListLayout")
infoLayout.FillDirection = Enum.FillDirection.Vertical
infoLayout.VerticalAlignment = Enum.VerticalAlignment.Center
infoLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
infoLayout.Parent = healthInfo

-- Health Bar Background
local healthBarBg = Instance.new("Frame")
healthBarBg.Name = "HealthBarBg"
healthBarBg.BackgroundColor3 = Color3.fromRGB(40, 40, 48)
healthBarBg.BackgroundTransparency = 0.2
healthBarBg.Size = UDim2.new(1, 0, 0, sizes.barHeight)
healthBarBg.Parent = healthInfo

-- Health Bar Background Corner
local barCorner = Instance.new("UICorner")
barCorner.CornerRadius = UDim.new(0, sizes.cornerRadius)
barCorner.Parent = healthBarBg

-- Health Bar Fill
local healthBarFill = Instance.new("Frame")
healthBarFill.Name = "HealthBarFill"
healthBarFill.BackgroundColor3 = CONFIG.COLORS.FULL
healthBarFill.BackgroundTransparency = 0.1
healthBarFill.Size = UDim2.new(1, 0, 1, 0)
healthBarFill.Parent = healthBarBg

-- Health Bar Fill Corner
local fillCorner = Instance.new("UICorner")
fillCorner.CornerRadius = UDim.new(0, sizes.cornerRadius)
fillCorner.Parent = healthBarFill

-- Health Text
local healthText = Instance.new("TextLabel")
healthText.Name = "HealthText"
healthText.BackgroundTransparency = 1
healthText.Text = "100/100"
healthText.Font = Enum.Font.GothamSemibold
healthText.TextSize = sizes.fontSize
healthText.TextColor3 = CONFIG.COLORS.TEXT
healthText.TextXAlignment = Enum.TextXAlignment.Left
healthText.Size = UDim2.new(1, 0, 0, sizes.fontSize + 2)
healthText.Parent = healthInfo

-- Health Variables
local humanoid = nil
local lastHealth = -1
local lastMaxHealth = -1
local isLowHealth = false
local pulseConnection = nil

-- Color Functions
local function getHealthColor(healthPercent)
	if healthPercent <= CONFIG.LOW_HEALTH_THRESHOLD then
		return CONFIG.COLORS.LOW
	elseif healthPercent <= 0.5 then
		return CONFIG.COLORS.MEDIUM
	else
		return CONFIG.COLORS.FULL
	end
end

-- Pulse Animation for Low Health
local function startPulse()
	if not CONFIG.PULSE_ON_LOW_HEALTH or pulseConnection then return end
	
	pulseConnection = RunService.Heartbeat:Connect(function()
		local time = tick()
		local pulse = math.sin(time * 3) * 0.1 + 1
		healthContainer.Size = UDim2.new(0, sizes.width * pulse, 0, sizes.height * pulse)
	end)
end

local function stopPulse()
	if pulseConnection then
		pulseConnection:Disconnect()
		pulseConnection = nil
		healthContainer.Size = UDim2.new(0, sizes.width, 0, sizes.height)
	end
end

-- Sound Functions
local function playLowHealthSound()
	if not CONFIG.ENABLE_SOUNDS then return end
	
	local sound = Instance.new("Sound")
	sound.SoundId = CONFIG.LOW_HEALTH_SOUND
	sound.Volume = 0.3
	sound.Parent = SoundService
	sound:Play()
	
	sound.Ended:Connect(function()
		sound:Destroy()
	end)
end

-- Update Health Function
local function updateHealth()
	if not humanoid then return end
	
	local currentHealth = humanoid.Health
	local maxHealth = humanoid.MaxHealth
	
	-- Skip update if no change
	if currentHealth == lastHealth and maxHealth == lastMaxHealth then return end
	
	lastHealth = currentHealth
	lastMaxHealth = maxHealth
	
	local healthPercent = currentHealth / maxHealth
	local healthColor = getHealthColor(healthPercent)
	local wasLowHealth = isLowHealth
	isLowHealth = healthPercent <= CONFIG.LOW_HEALTH_THRESHOLD
	
	-- Update health bar with smooth animation
	healthBarFill.BackgroundColor3 = healthColor
	TweenService:Create(healthBarFill, TweenInfo.new(CONFIG.ANIMATION_SPEED, Enum.EasingStyle.Quad, Enum.EasingDirection.Out), {
		Size = UDim2.new(healthPercent, 0, 1, 0)
	}):Play()
	
	-- Update health text
	healthText.Text = string.format("%d/%d", math.floor(currentHealth + 0.5), math.floor(maxHealth + 0.5))
	
	-- Update icon color based on health
	healthIcon.ImageColor3 = healthColor
	
	-- Handle low health effects
	if isLowHealth and not wasLowHealth then
		startPulse()
		playLowHealthSound()
	elseif not isLowHealth and wasLowHealth then
		stopPulse()
	end
end

-- Bind Humanoid
local function bindHumanoid(h)
	humanoid = h
	
	-- Connect health events
	humanoid.HealthChanged:Connect(updateHealth)
	humanoid:GetPropertyChangedSignal("MaxHealth"):Connect(updateHealth)
	
	-- Initial update
	updateHealth()
end

-- Setup Character
local function setupCharacter()
	local character = LOCAL_PLAYER.Character
	if character then
		local h = character:FindFirstChildOfClass("Humanoid")
		if h then
			bindHumanoid(h)
		else
			character.ChildAdded:Connect(function(child)
				if child:IsA("Humanoid") then
					bindHumanoid(child)
				end
			end)
		end
	end
end

-- Handle Character Added
LOCAL_PLAYER.CharacterAdded:Connect(function(character)
	task.wait(0.1) -- Small delay to ensure humanoid is loaded
	setupCharacter()
end)

-- Handle Humanoid Added (for respawn)
LOCAL_PLAYER.CharacterAdded:Connect(function(character)
	character.ChildAdded:Connect(function(child)
		if child:IsA("Humanoid") then
			bindHumanoid(child)
		end
	end)
end)

-- Responsive Layout Function
local function updateLayout()
	-- Get current device type and update sizes
	local newSizes = getHealthSizes()
	sizes = newSizes
	
	-- Update container size
	healthContainer.Size = UDim2.new(0, sizes.width, 0, sizes.height)
	
	-- Update icon size
	healthIcon.Size = UDim2.new(0, sizes.iconSize, 0, sizes.iconSize)
	
	-- Update bar height
	healthBarBg.Size = UDim2.new(1, 0, 0, sizes.barHeight)
	
	-- Update font size
	healthText.TextSize = sizes.fontSize
	healthText.Size = UDim2.new(1, 0, 0, sizes.fontSize + 2)
	
	-- Update padding
	padding.PaddingLeft = UDim.new(0, sizes.padding)
	padding.PaddingRight = UDim.new(0, sizes.padding)
	padding.PaddingTop = UDim.new(0, sizes.padding)
	padding.PaddingBottom = UDim.new(0, sizes.padding)
	
	-- Update layout spacing
	layout.Padding = UDim.new(0, sizes.spacing)
	
	-- Update corner radius
	corner.CornerRadius = UDim.new(0, sizes.cornerRadius)
	barCorner.CornerRadius = UDim.new(0, sizes.cornerRadius)
	fillCorner.CornerRadius = UDim.new(0, sizes.cornerRadius)
	
	-- Update position
	healthContainer.Position = POSITIONS[CONFIG.POSITION]()
end

-- Position Change Function
local function changePosition(newPosition)
	if not POSITIONS[newPosition] then return end
	
	CONFIG.POSITION = newPosition
	healthContainer.AnchorPoint = ANCHORS[newPosition]
	healthContainer.Position = POSITIONS[newPosition]()
end

-- Monitor device changes
UserInputService:GetPropertyChangedSignal("TouchEnabled"):Connect(updateLayout)
UserInputService:GetPropertyChangedSignal("GamepadEnabled"):Connect(updateLayout)
UserInputService:GetPropertyChangedSignal("KeyboardEnabled"):Connect(updateLayout)

-- Monitor viewport changes
local camera = workspace.CurrentCamera
if camera then
	camera:GetPropertyChangedSignal("ViewportSize"):Connect(updateLayout)
end

-- Initialize
setupCharacter()
disableOverheadHealth()

-- Global functions for external control
_G.AdvancedHealthSystem = {
	-- Show/Hide health bar
	show = function()
		healthContainer.Visible = true
	end,
	
	hide = function()
		healthContainer.Visible = false
	end,
	
	-- Toggle visibility
	toggle = function()
		healthContainer.Visible = not healthContainer.Visible
	end,
	
	-- Change position
	setPosition = function(position)
		changePosition(position)
	end,
	
	-- Get current health info
	getHealth = function()
		if humanoid then
			return {
				health = humanoid.Health,
				maxHealth = humanoid.MaxHealth,
				percent = humanoid.Health / humanoid.MaxHealth,
				isLowHealth = isLowHealth
			}
		end
		return nil
	end,
	
	-- Get device type
	getDeviceType = getDeviceType,
	
	-- Update layout
	updateLayout = updateLayout,
	
	-- Configuration
	config = CONFIG,
	
	-- Get available positions
	getPositions = function()
		return {"BottomLeft", "BottomRight", "TopLeft", "TopRight"}
	end
}

-- Commands for testing
print("🔧 COMMANDS UNTUK TEST ADVANCED HEALTH SYSTEM:")
print("_G.AdvancedHealthSystem.show() - Show health bar")
print("_G.AdvancedHealthSystem.hide() - Hide health bar")
print("_G.AdvancedHealthSystem.toggle() - Toggle health bar")
print("_G.AdvancedHealthSystem.setPosition('BottomRight') - Change position")
print("_G.AdvancedHealthSystem.getHealth() - Get health info")
print("_G.AdvancedHealthSystem.getDeviceType() - Get device type")
print("_G.AdvancedHealthSystem.updateLayout() - Update layout")
print("_G.AdvancedHealthSystem.getPositions() - Get available positions")
print("")
print("📱 DEVICE-SPECIFIC SIZES:")
print("Mobile: 140x22px, Font 9px, Icon 10px")
print("Console: 160x26px, Font 10px, Icon 12px")
print("Desktop: 180x30px, Font 11px, Icon 14px")
print("")
print("🎯 POSITIONS:")
print("- BottomLeft (default)")
print("- BottomRight")
print("- TopLeft")
print("- TopRight")
print("")
print("✅ ADVANCED FEATURES:")
print("- Default HP dipindahkan ke kiri pojok bawah")
print("- Overhead HP dinonaktifkan untuk semua player")
print("- Model baru yang simple dan clean")
print("- Ukuran dioptimalkan untuk Mobile dan Console")
print("- Responsive layout yang menyesuaikan device")
print("- Smooth animations dengan color changes")
print("- Pulse animation untuk low health")
print("- Sound effects untuk low health")
print("- 4 posisi berbeda (kiri/kanan, atas/bawah)")
print("- Auto-detect device type")
print("- No bugs atau errors")
print("")
print("🚀 ADVANCED HEALTH SYSTEM READY!")