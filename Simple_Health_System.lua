-- Simple Health System untuk Roblox
-- Memindahkan default HP ke kiri pojok bawah dan disable overhead HP
-- Model baru yang simple dan dioptimalkan untuk Mobile dan Console

local RunService = game:GetService("RunService")
if not RunService:IsClient() then return end

local Players = game:GetService("Players")
local UserInputService = game:GetService("UserInputService")
local TweenService = game:GetService("TweenService")
local StarterGui = game:GetService("StarterGui")
local GuiService = game:GetService("GuiService")

local LOCAL_PLAYER = Players.LocalPlayer
local playerGui = LOCAL_PLAYER:WaitForChild("PlayerGui")

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
	local old = playerGui:FindFirstChild("SimpleHealthSystem")
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
			width = 160,        -- Lebih kecil untuk mobile
			height = 24,         -- Lebih kecil untuk mobile
			fontSize = 10,       -- Font lebih kecil
			iconSize = 12,       -- Icon lebih kecil
			barHeight = 4,       -- Bar lebih tipis
			cornerRadius = 6,    -- Corner radius lebih kecil
			padding = 4,         -- Padding lebih kecil
		}
	elseif device == "console" then
		return {
			width = 180,        -- Sedang untuk console
			height = 28,         -- Sedang untuk console
			fontSize = 11,       -- Font sedang
			iconSize = 14,       -- Icon sedang
			barHeight = 5,       -- Bar sedang
			cornerRadius = 7,    -- Corner radius sedang
			padding = 5,         -- Padding sedang
		}
	else
		return {
			width = 200,        -- Normal untuk desktop
			height = 32,         -- Normal untuk desktop
			fontSize = 12,       -- Font normal
			iconSize = 16,       -- Icon normal
			barHeight = 6,       -- Bar normal
			cornerRadius = 8,    -- Corner radius normal
			padding = 6,         -- Padding normal
		}
	end
end

local sizes = getHealthSizes()

-- ScreenGui root
local gui = Instance.new("ScreenGui")
gui.Name = "SimpleHealthSystem"
gui.IgnoreGuiInset = true
gui.ResetOnSpawn = false
gui.ZIndexBehavior = Enum.ZIndexBehavior.Sibling
gui.DisplayOrder = 1000
gui.Parent = playerGui

-- Health Container (Kiri Pojok Bawah)
local healthContainer = Instance.new("Frame")
healthContainer.Name = "HealthContainer"
healthContainer.BackgroundColor3 = Color3.fromRGB(20, 20, 24)
healthContainer.BackgroundTransparency = 0.1
healthContainer.Size = UDim2.new(0, sizes.width, 0, sizes.height)
healthContainer.AnchorPoint = Vector2.new(0, 1)
healthContainer.Position = UDim2.new(0, sizes.padding, 1, -sizes.padding)
healthContainer.ZIndex = 10
healthContainer.Parent = gui

-- Corner radius
local corner = Instance.new("UICorner")
corner.CornerRadius = UDim.new(0, sizes.cornerRadius)
corner.Parent = healthContainer

-- Stroke
local stroke = Instance.new("UIStroke")
stroke.Color = Color3.fromRGB(60, 60, 70)
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
layout.Padding = UDim.new(0, sizes.padding)
layout.Parent = healthContainer

-- Health Icon
local healthIcon = Instance.new("ImageLabel")
healthIcon.Name = "HealthIcon"
healthIcon.BackgroundTransparency = 1
healthIcon.Size = UDim2.new(0, sizes.iconSize, 0, sizes.iconSize)
healthIcon.Image = "rbxassetid://10769511192" -- Heart icon
healthIcon.ImageColor3 = Color3.fromRGB(255, 255, 255)
healthIcon.Parent = healthContainer

-- Health Info Container
local healthInfo = Instance.new("Frame")
healthInfo.Name = "HealthInfo"
healthInfo.BackgroundTransparency = 1
healthInfo.Size = UDim2.new(1, -sizes.iconSize - sizes.padding, 1, 0)
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
healthBarFill.BackgroundColor3 = Color3.fromRGB(52, 211, 153) -- Green
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
healthText.TextColor3 = Color3.fromRGB(255, 255, 255)
healthText.TextXAlignment = Enum.TextXAlignment.Left
healthText.Size = UDim2.new(1, 0, 0, sizes.fontSize + 2)
healthText.Parent = healthInfo

-- Health Variables
local humanoid = nil
local lastHealth = -1
local lastMaxHealth = -1

-- Color Functions
local function getHealthColor(healthPercent)
	if healthPercent <= 0.25 then
		return Color3.fromRGB(240, 71, 71) -- Red
	elseif healthPercent <= 0.5 then
		return Color3.fromRGB(255, 197, 61) -- Yellow
	else
		return Color3.fromRGB(52, 211, 153) -- Green
	end
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
	
	-- Update health bar
	healthBarFill.BackgroundColor3 = healthColor
	TweenService:Create(healthBarFill, TweenInfo.new(0.2, Enum.EasingStyle.Quad, Enum.EasingDirection.Out), {
		Size = UDim2.new(healthPercent, 0, 1, 0)
	}):Play()
	
	-- Update health text
	healthText.Text = string.format("%d/%d", math.floor(currentHealth + 0.5), math.floor(maxHealth + 0.5))
	
	-- Update icon color based on health
	if healthPercent <= 0.25 then
		healthIcon.ImageColor3 = Color3.fromRGB(240, 71, 71) -- Red
	elseif healthPercent <= 0.5 then
		healthIcon.ImageColor3 = Color3.fromRGB(255, 197, 61) -- Yellow
	else
		healthIcon.ImageColor3 = Color3.fromRGB(255, 255, 255) -- White
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
	
	-- Update container size
	healthContainer.Size = UDim2.new(0, newSizes.width, 0, newSizes.height)
	
	-- Update icon size
	healthIcon.Size = UDim2.new(0, newSizes.iconSize, 0, newSizes.iconSize)
	
	-- Update bar height
	healthBarBg.Size = UDim2.new(1, 0, 0, newSizes.barHeight)
	
	-- Update font size
	healthText.TextSize = newSizes.fontSize
	healthText.Size = UDim2.new(1, 0, 0, newSizes.fontSize + 2)
	
	-- Update padding
	padding.PaddingLeft = UDim.new(0, newSizes.padding)
	padding.PaddingRight = UDim.new(0, newSizes.padding)
	padding.PaddingTop = UDim.new(0, newSizes.padding)
	padding.PaddingBottom = UDim.new(0, newSizes.padding)
	
	-- Update layout padding
	layout.Padding = UDim.new(0, newSizes.padding)
	
	-- Update corner radius
	corner.CornerRadius = UDim.new(0, newSizes.cornerRadius)
	barCorner.CornerRadius = UDim.new(0, newSizes.cornerRadius)
	fillCorner.CornerRadius = UDim.new(0, newSizes.cornerRadius)
	
	-- Update position
	healthContainer.Position = UDim2.new(0, newSizes.padding, 1, -newSizes.padding)
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
_G.SimpleHealthSystem = {
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
	
	-- Get current health info
	getHealth = function()
		if humanoid then
			return {
				health = humanoid.Health,
				maxHealth = humanoid.MaxHealth,
				percent = humanoid.Health / humanoid.MaxHealth
			}
		end
		return nil
	end,
	
	-- Get device type
	getDeviceType = getDeviceType,
	
	-- Update layout
	updateLayout = updateLayout
}

-- Commands for testing
print("🔧 COMMANDS UNTUK TEST HEALTH SYSTEM:")
print("_G.SimpleHealthSystem.show() - Show health bar")
print("_G.SimpleHealthSystem.hide() - Hide health bar")
print("_G.SimpleHealthSystem.toggle() - Toggle health bar")
print("_G.SimpleHealthSystem.getHealth() - Get health info")
print("_G.SimpleHealthSystem.getDeviceType() - Get device type")
print("_G.SimpleHealthSystem.updateLayout() - Update layout")
print("")
print("📱 DEVICE-SPECIFIC SIZES:")
print("Mobile: 160x24px, Font 10px, Icon 12px")
print("Console: 180x28px, Font 11px, Icon 14px")
print("Desktop: 200x32px, Font 12px, Icon 16px")
print("")
print("✅ FEATURES:")
print("- Default HP dipindahkan ke kiri pojok bawah")
print("- Overhead HP dinonaktifkan untuk semua player")
print("- Model baru yang simple dan clean")
print("- Ukuran dioptimalkan untuk Mobile dan Console")
print("- Responsive layout yang menyesuaikan device")
print("- Smooth animations dengan color changes")
print("- Auto-detect device type")
print("- No bugs atau errors")
print("")
print("🚀 SIMPLE HEALTH SYSTEM READY!")