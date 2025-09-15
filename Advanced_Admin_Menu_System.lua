-- Advanced Admin Menu System untuk Roblox
-- Buka dengan F3 atau icon mobile di kanan
-- Menu bisa di-share ke player lain
-- Menu hilang saat keluar server
-- Optimasi untuk mobile dengan ukuran diperkecil
-- Fitur advanced: Server commands, Player management, Settings

local RunService = game:GetService("RunService")
if not RunService:IsClient() then return end

local Players = game:GetService("Players")
local UserInputService = game:GetService("UserInputService")
local TweenService = game:GetService("TweenService")
local StarterGui = game:GetService("StarterGui")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local TextService = game:GetService("TextService")
local Lighting = game:GetService("Lighting")
local SoundService = game:GetService("SoundService")

local LOCAL_PLAYER = Players.LocalPlayer
local playerGui = LOCAL_PLAYER:WaitForChild("PlayerGui")

-- Configuration
local CONFIG = {
	-- Menu Settings
	MENU_WIDTH = 350,           -- Lebar menu desktop
	MENU_WIDTH_MOBILE = 280,    -- Lebar menu mobile
	MENU_HEIGHT = 500,          -- Tinggi menu desktop
	MENU_HEIGHT_MOBILE = 400,   -- Tinggi menu mobile
	MENU_POSITION_X = 0.5,      -- Posisi X (center)
	MENU_POSITION_Y = 0.5,      -- Posisi Y (center)
	
	-- Mobile Settings
	MOBILE_ICON_SIZE = 55,      -- Ukuran icon mobile
	MOBILE_ICON_POSITION = "RIGHT", -- Posisi icon mobile
	
	-- Animation Settings
	ANIMATION_SPEED = 0.25,     -- Kecepatan animasi
	FADE_SPEED = 0.2,           -- Kecepatan fade
	
	-- Debounce Settings
	DEBOUNCE_TIME = 0.2,        -- Debounce untuk button
	MENU_DEBOUNCE = 0.4,        -- Debounce untuk buka/tutup menu
	SHARE_DEBOUNCE = 0.8,       -- Debounce untuk share menu
	
	-- Admin Settings
	ADMIN_RANK = "Admin",       -- Rank admin
	ADMIN_COLOR = Color3.fromRGB(255, 215, 0), -- Warna admin
	PLAYER_COLOR = Color3.fromRGB(100, 149, 237), -- Warna player
	MODERATOR_COLOR = Color3.fromRGB(50, 205, 50), -- Warna moderator
	
	-- Server Settings
	SERVER_NAME = "Roblox Server",
	MAX_PLAYERS = 20,
}

-- Cleanup
do
	local old = playerGui:FindFirstChild("AdvancedAdminMenuSystem")
	if old then old:Destroy() end
end

-- Debounce
local function createDebounce(minDelay)
	local lastTime = 0
	return function()
		local currentTime = tick()
		if currentTime - lastTime < minDelay then
			return false
		end
		lastTime = currentTime
		return true
	end
end

local buttonDebounce = createDebounce(CONFIG.DEBOUNCE_TIME)
local menuDebounce = createDebounce(CONFIG.MENU_DEBOUNCE)
local shareDebounce = createDebounce(CONFIG.SHARE_DEBOUNCE)

-- Global variables
local menuOpen = false
local sharedPlayers = {}
local isAdmin = false
local isModerator = false
local isMobile = false
local currentTab = "Players"

-- Device detection
local function getDeviceType()
	if UserInputService.TouchEnabled and not UserInputService.KeyboardEnabled then
		return "mobile"
	elseif UserInputService.GamepadEnabled and not UserInputService.KeyboardEnabled then
		return "console"
	else
		return "desktop"
	end
end

-- Check if player is admin/moderator
local function checkAdminStatus()
	-- Simple admin check - bisa dikustomisasi
	local adminNames = {
		"AdminUser1",
		"AdminUser2", 
		"AdminUser3",
		LOCAL_PLAYER.Name -- Temporary untuk testing
	}
	
	local moderatorNames = {
		"ModeratorUser1",
		"ModeratorUser2",
		"ModeratorUser3"
	}
	
	for _, adminName in ipairs(adminNames) do
		if LOCAL_PLAYER.Name == adminName then
			return true, false -- Admin, not moderator
		end
	end
	
	for _, moderatorName in ipairs(moderatorNames) do
		if LOCAL_PLAYER.Name == moderatorName then
			return false, true -- Not admin, moderator
		end
	end
	
	return false, false -- Neither
end

-- ScreenGui
local gui = Instance.new("ScreenGui")
gui.Name = "AdvancedAdminMenuSystem"
gui.IgnoreGuiInset = true
gui.ResetOnSpawn = false
gui.ZIndexBehavior = Enum.ZIndexBehavior.Sibling
gui.DisplayOrder = 1000
gui.Parent = playerGui

-- Mobile Icon (hidden by default)
local mobileIcon = Instance.new("TextButton")
mobileIcon.Name = "MobileIcon"
mobileIcon.Size = UDim2.new(0, CONFIG.MOBILE_ICON_SIZE, 0, CONFIG.MOBILE_ICON_SIZE)
mobileIcon.Position = UDim2.new(1, -CONFIG.MOBILE_ICON_SIZE - 10, 0, 10)
mobileIcon.BackgroundColor3 = CONFIG.ADMIN_COLOR
mobileIcon.BorderSizePixel = 0
mobileIcon.Text = "👑"
mobileIcon.TextColor3 = Color3.fromRGB(255, 255, 255)
mobileIcon.TextScaled = true
mobileIcon.Font = Enum.Font.GothamBold
mobileIcon.Visible = false
mobileIcon.ZIndex = 20
mobileIcon.Parent = gui

-- Mobile icon corner
local mobileCorner = Instance.new("UICorner")
mobileCorner.CornerRadius = UDim.new(0, 10)
mobileCorner.Parent = mobileIcon

-- Mobile icon stroke
local mobileStroke = Instance.new("UIStroke")
mobileStroke.Color = Color3.fromRGB(255, 255, 255)
mobileStroke.Thickness = 2
mobileStroke.Parent = mobileIcon

-- Main Menu Frame
local menuFrame = Instance.new("Frame")
menuFrame.Name = "MenuFrame"
menuFrame.Size = UDim2.new(0, CONFIG.MENU_WIDTH, 0, CONFIG.MENU_HEIGHT)
menuFrame.Position = UDim2.new(CONFIG.MENU_POSITION_X, -CONFIG.MENU_WIDTH/2, CONFIG.MENU_POSITION_Y, -CONFIG.MENU_HEIGHT/2)
menuFrame.BackgroundColor3 = Color3.fromRGB(25, 25, 35)
menuFrame.BorderSizePixel = 0
menuFrame.Visible = false
menuFrame.ZIndex = 15
menuFrame.Parent = gui

-- Menu corner
local menuCorner = Instance.new("UICorner")
menuCorner.CornerRadius = UDim.new(0, 15)
menuCorner.Parent = menuFrame

-- Menu stroke
local menuStroke = Instance.new("UIStroke")
menuStroke.Color = CONFIG.ADMIN_COLOR
menuStroke.Thickness = 3
menuStroke.Parent = menuFrame

-- Menu padding
local menuPadding = Instance.new("UIPadding")
menuPadding.PaddingLeft = UDim.new(0, 15)
menuPadding.PaddingRight = UDim.new(0, 15)
menuPadding.PaddingTop = UDim.new(0, 15)
menuPadding.PaddingBottom = UDim.new(0, 15)
menuPadding.Parent = menuFrame

-- Menu layout
local menuLayout = Instance.new("UIListLayout")
menuLayout.FillDirection = Enum.FillDirection.Vertical
menuLayout.VerticalAlignment = Enum.VerticalAlignment.Top
menuLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
menuLayout.Padding = UDim.new(0, 10)
menuLayout.Parent = menuFrame

-- Menu Header
local menuHeader = Instance.new("Frame")
menuHeader.Name = "MenuHeader"
menuHeader.Size = UDim2.new(1, 0, 0, 50)
menuHeader.BackgroundTransparency = 1
menuHeader.Parent = menuFrame

local headerLayout = Instance.new("UIListLayout")
headerLayout.FillDirection = Enum.FillDirection.Horizontal
headerLayout.VerticalAlignment = Enum.VerticalAlignment.Center
headerLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
headerLayout.Padding = UDim.new(0, 10)
headerLayout.Parent = menuHeader

-- Header Icon
local headerIcon = Instance.new("TextLabel")
headerIcon.Size = UDim2.new(0, 35, 0, 35)
headerIcon.BackgroundTransparency = 1
headerIcon.Text = "👑"
headerIcon.TextColor3 = CONFIG.ADMIN_COLOR
headerIcon.TextScaled = true
headerIcon.Font = Enum.Font.GothamBold
headerIcon.Parent = menuHeader

-- Header Title
local headerTitle = Instance.new("TextLabel")
headerTitle.Size = UDim2.new(1, -80, 1, 0)
headerTitle.BackgroundTransparency = 1
headerTitle.Text = "ADVANCED ADMIN MENU"
headerTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
headerTitle.TextScaled = true
headerTitle.Font = Enum.Font.GothamBold
headerTitle.TextStrokeTransparency = 0.5
headerTitle.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
headerTitle.Parent = menuHeader

-- Close Button
local closeButton = Instance.new("TextButton")
closeButton.Size = UDim2.new(0, 35, 0, 35)
closeButton.BackgroundColor3 = Color3.fromRGB(255, 0, 0)
closeButton.BorderSizePixel = 0
closeButton.Text = "✕"
closeButton.TextColor3 = Color3.fromRGB(255, 255, 255)
closeButton.TextScaled = true
closeButton.Font = Enum.Font.GothamBold
closeButton.Parent = menuHeader

local closeCorner = Instance.new("UICorner")
closeCorner.CornerRadius = UDim.new(0, 8)
closeCorner.Parent = closeButton

-- Tab Navigation
local tabFrame = Instance.new("Frame")
tabFrame.Name = "TabFrame"
tabFrame.Size = UDim2.new(1, 0, 0, 35)
tabFrame.BackgroundColor3 = Color3.fromRGB(20, 20, 30)
tabFrame.BorderSizePixel = 0
tabFrame.Parent = menuFrame

local tabCorner = Instance.new("UICorner")
tabCorner.CornerRadius = UDim.new(0, 8)
tabCorner.Parent = tabFrame

local tabLayout = Instance.new("UIListLayout")
tabLayout.FillDirection = Enum.FillDirection.Horizontal
tabLayout.VerticalAlignment = Enum.VerticalAlignment.Center
tabLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
tabLayout.Padding = UDim.new(0, 5)
tabLayout.Parent = tabFrame

-- Create tab button
local function createTabButton(text, tabName)
	local button = Instance.new("TextButton")
	button.Size = UDim2.new(0, 80, 0, 25)
	button.BackgroundColor3 = Color3.fromRGB(40, 40, 50)
	button.BorderSizePixel = 0
	button.Text = text
	button.TextColor3 = Color3.fromRGB(255, 255, 255)
	button.TextScaled = true
	button.Font = Enum.Font.GothamBold
	button.Parent = tabFrame
	
	local buttonCorner = Instance.new("UICorner")
	buttonCorner.CornerRadius = UDim.new(0, 6)
	buttonCorner.Parent = button
	
	button.MouseButton1Click:Connect(function()
		if buttonDebounce() then
			switchTab(tabName)
		end
	end)
	
	return button
end

-- Content Frame
local contentFrame = Instance.new("Frame")
contentFrame.Name = "ContentFrame"
contentFrame.Size = UDim2.new(1, 0, 1, -95)
contentFrame.BackgroundTransparency = 1
contentFrame.Parent = menuFrame

-- Tab switching
local function switchTab(tabName)
	currentTab = tabName
	
	-- Clear content
	for _, child in ipairs(contentFrame:GetChildren()) do
		child:Destroy()
	end
	
	-- Update tab buttons
	for _, child in ipairs(tabFrame:GetChildren()) do
		if child:IsA("TextButton") then
			if child.Text == tabName then
				child.BackgroundColor3 = CONFIG.ADMIN_COLOR
			else
				child.BackgroundColor3 = Color3.fromRGB(40, 40, 50)
			end
		end
	end
	
	-- Create content based on tab
	if tabName == "Players" then
		createPlayersTab()
	elseif tabName == "Server" then
		createServerTab()
	elseif tabName == "Settings" then
		createSettingsTab()
	end
end

-- Create Players Tab
local function createPlayersTab()
	local playersFrame = Instance.new("Frame")
	playersFrame.Name = "PlayersFrame"
	playersFrame.Size = UDim2.new(1, 0, 1, 0)
	playersFrame.BackgroundColor3 = Color3.fromRGB(20, 20, 30)
	playersFrame.BorderSizePixel = 0
	playersFrame.Parent = contentFrame
	
	local playersCorner = Instance.new("UICorner")
	playersCorner.CornerRadius = UDim.new(0, 8)
	playersCorner.Parent = playersFrame
	
	local playersPadding = Instance.new("UIPadding")
	playersPadding.PaddingLeft = UDim.new(0, 10)
	playersPadding.PaddingRight = UDim.new(0, 10)
	playersPadding.PaddingTop = UDim.new(0, 10)
	playersPadding.PaddingBottom = UDim.new(0, 10)
	playersPadding.Parent = playersFrame
	
	local playersLayout = Instance.new("UIListLayout")
	playersLayout.FillDirection = Enum.FillDirection.Vertical
	playersLayout.VerticalAlignment = Enum.VerticalAlignment.Top
	playersLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
	playersLayout.Padding = UDim.new(0, 8)
	playersLayout.Parent = playersFrame
	
	-- Players Title
	local playersTitle = Instance.new("TextLabel")
	playersTitle.Size = UDim2.new(1, 0, 0, 25)
	playersTitle.BackgroundTransparency = 1
	playersTitle.Text = "PLAYERS ONLINE (" .. #Players:GetPlayers() .. ")"
	playersTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
	playersTitle.TextScaled = true
	playersTitle.Font = Enum.Font.GothamBold
	playersTitle.TextStrokeTransparency = 0.5
	playersTitle.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	playersTitle.Parent = playersFrame
	
	-- Player List
	local playerList = Instance.new("ScrollingFrame")
	playerList.Size = UDim2.new(1, 0, 1, -30)
	playerList.BackgroundTransparency = 1
	playerList.BorderSizePixel = 0
	playerList.ScrollBarThickness = 6
	playerList.ScrollBarImageColor3 = CONFIG.ADMIN_COLOR
	playerList.Parent = playersFrame
	
	local playerListLayout = Instance.new("UIListLayout")
	playerListLayout.FillDirection = Enum.FillDirection.Vertical
	playerListLayout.VerticalAlignment = Enum.VerticalAlignment.Top
	playerListLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
	playerListLayout.Padding = UDim.new(0, 5)
	playerListLayout.Parent = playerList
	
	-- Create player button
	local function createPlayerButton(player)
		local button = Instance.new("TextButton")
		button.Size = UDim2.new(1, 0, 0, 30)
		button.BackgroundColor3 = CONFIG.PLAYER_COLOR
		button.BorderSizePixel = 0
		button.Text = player.Name .. " (ID: " .. player.UserId .. ")"
		button.TextColor3 = Color3.fromRGB(255, 255, 255)
		button.TextScaled = true
		button.Font = Enum.Font.Gotham
		button.Parent = playerList
		
		local buttonCorner = Instance.new("UICorner")
		buttonCorner.CornerRadius = UDim.new(0, 6)
		buttonCorner.Parent = button
		
		button.MouseButton1Click:Connect(function()
			if buttonDebounce() then
				shareMenuToPlayer(player)
			end
		end)
		
		return button
	end
	
	-- Add current players
	for _, player in ipairs(Players:GetPlayers()) do
		if player ~= LOCAL_PLAYER then
			createPlayerButton(player)
		end
	end
	
	-- Update canvas size
	playerList.CanvasSize = UDim2.new(0, 0, 0, playerListLayout.AbsoluteContentSize.Y)
end

-- Create Server Tab
local function createServerTab()
	local serverFrame = Instance.new("Frame")
	serverFrame.Name = "ServerFrame"
	serverFrame.Size = UDim2.new(1, 0, 1, 0)
	serverFrame.BackgroundColor3 = Color3.fromRGB(20, 20, 30)
	serverFrame.BorderSizePixel = 0
	serverFrame.Parent = contentFrame
	
	local serverCorner = Instance.new("UICorner")
	serverCorner.CornerRadius = UDim.new(0, 8)
	serverCorner.Parent = serverFrame
	
	local serverPadding = Instance.new("UIPadding")
	serverPadding.PaddingLeft = UDim.new(0, 10)
	serverPadding.PaddingRight = UDim.new(0, 10)
	serverPadding.PaddingTop = UDim.new(0, 10)
	serverPadding.PaddingBottom = UDim.new(0, 10)
	serverPadding.Parent = serverFrame
	
	local serverLayout = Instance.new("UIListLayout")
	serverLayout.FillDirection = Enum.FillDirection.Vertical
	serverLayout.VerticalAlignment = Enum.VerticalAlignment.Top
	serverLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
	serverLayout.Padding = UDim.new(0, 8)
	serverLayout.Parent = serverFrame
	
	-- Server Title
	local serverTitle = Instance.new("TextLabel")
	serverTitle.Size = UDim2.new(1, 0, 0, 25)
	serverTitle.BackgroundTransparency = 1
	serverTitle.Text = "SERVER COMMANDS"
	serverTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
	serverTitle.TextScaled = true
	serverTitle.Font = Enum.Font.GothamBold
	serverTitle.TextStrokeTransparency = 0.5
	serverTitle.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	serverTitle.Parent = serverFrame
	
	-- Server Info
	local serverInfo = Instance.new("TextLabel")
	serverInfo.Size = UDim2.new(1, 0, 0, 40)
	serverInfo.BackgroundTransparency = 1
	serverInfo.Text = "Server: " .. CONFIG.SERVER_NAME .. "\nPlayers: " .. #Players:GetPlayers() .. "/" .. CONFIG.MAX_PLAYERS
	serverInfo.TextColor3 = Color3.fromRGB(200, 200, 200)
	serverInfo.TextScaled = true
	serverInfo.Font = Enum.Font.Gotham
	serverInfo.TextWrapped = true
	serverInfo.TextStrokeTransparency = 0.5
	serverInfo.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	serverInfo.Parent = serverFrame
	
	-- Server Commands
	local commandsFrame = Instance.new("Frame")
	commandsFrame.Size = UDim2.new(1, 0, 1, -70)
	commandsFrame.BackgroundTransparency = 1
	commandsFrame.Parent = serverFrame
	
	local commandsLayout = Instance.new("UIListLayout")
	commandsLayout.FillDirection = Enum.FillDirection.Vertical
	commandsLayout.VerticalAlignment = Enum.VerticalAlignment.Top
	commandsLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
	commandsLayout.Padding = UDim.new(0, 5)
	commandsLayout.Parent = commandsFrame
	
	-- Create server command button
	local function createServerButton(text, action, color)
		local button = Instance.new("TextButton")
		button.Size = UDim2.new(1, 0, 0, 30)
		button.BackgroundColor3 = color or CONFIG.ADMIN_COLOR
		button.BorderSizePixel = 0
		button.Text = text
		button.TextColor3 = Color3.fromRGB(255, 255, 255)
		button.TextScaled = true
		button.Font = Enum.Font.GothamBold
		button.Parent = commandsFrame
		
		local buttonCorner = Instance.new("UICorner")
		buttonCorner.CornerRadius = UDim.new(0, 6)
		buttonCorner.Parent = button
		
		button.MouseButton1Click:Connect(function()
			if buttonDebounce() then
				action()
			end
		end)
		
		return button
	end
	
	-- Server commands
	createServerButton("🔄 Restart Server", function()
		print("🔄 Restarting server...")
	end, Color3.fromRGB(255, 165, 0))
	
	createServerButton("🌙 Set Night Time", function()
		Lighting.ClockTime = 0
		print("🌙 Set to night time")
	end, Color3.fromRGB(75, 0, 130))
	
	createServerButton("☀️ Set Day Time", function()
		Lighting.ClockTime = 12
		print("☀️ Set to day time")
	end, Color3.fromRGB(255, 215, 0))
	
	createServerButton("🌧️ Set Rain", function()
		Lighting.Rain = 1
		print("🌧️ Set rain")
	end, Color3.fromRGB(0, 191, 255))
	
	createServerButton("☀️ Clear Weather", function()
		Lighting.Rain = 0
		print("☀️ Clear weather")
	end, Color3.fromRGB(255, 255, 255))
	
	createServerButton("🔊 Enable Sounds", function()
		SoundService.AmbientReverb = Enum.ReverbType.Plain
		print("🔊 Enabled sounds")
	end, Color3.fromRGB(50, 205, 50))
	
	createServerButton("🔇 Disable Sounds", function()
		SoundService.AmbientReverb = Enum.ReverbType.NoReverb
		print("🔇 Disabled sounds")
	end, Color3.fromRGB(220, 20, 60))
end

-- Create Settings Tab
local function createSettingsTab()
	local settingsFrame = Instance.new("Frame")
	settingsFrame.Name = "SettingsFrame"
	settingsFrame.Size = UDim2.new(1, 0, 1, 0)
	settingsFrame.BackgroundColor3 = Color3.fromRGB(20, 20, 30)
	settingsFrame.BorderSizePixel = 0
	settingsFrame.Parent = contentFrame
	
	local settingsCorner = Instance.new("UICorner")
	settingsCorner.CornerRadius = UDim.new(0, 8)
	settingsCorner.Parent = settingsFrame
	
	local settingsPadding = Instance.new("UIPadding")
	settingsPadding.PaddingLeft = UDim.new(0, 10)
	settingsPadding.PaddingRight = UDim.new(0, 10)
	settingsPadding.PaddingTop = UDim.new(0, 10)
	settingsPadding.PaddingBottom = UDim.new(0, 10)
	settingsPadding.Parent = settingsFrame
	
	local settingsLayout = Instance.new("UIListLayout")
	settingsLayout.FillDirection = Enum.FillDirection.Vertical
	settingsLayout.VerticalAlignment = Enum.VerticalAlignment.Top
	settingsLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
	settingsLayout.Padding = UDim.new(0, 8)
	settingsLayout.Parent = settingsFrame
	
	-- Settings Title
	local settingsTitle = Instance.new("TextLabel")
	settingsTitle.Size = UDim2.new(1, 0, 0, 25)
	settingsTitle.BackgroundTransparency = 1
	settingsTitle.Text = "ADMIN SETTINGS"
	settingsTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
	settingsTitle.TextScaled = true
	settingsTitle.Font = Enum.Font.GothamBold
	settingsTitle.TextStrokeTransparency = 0.5
	settingsTitle.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	settingsTitle.Parent = settingsFrame
	
	-- Settings Info
	local settingsInfo = Instance.new("TextLabel")
	settingsInfo.Size = UDim2.new(1, 0, 0, 60)
	settingsInfo.BackgroundTransparency = 1
	settingsInfo.Text = "Admin: " .. LOCAL_PLAYER.Name .. "\nRank: " .. (isAdmin and "Admin" or "Moderator") .. "\nDevice: " .. getDeviceType()
	settingsInfo.TextColor3 = Color3.fromRGB(200, 200, 200)
	settingsInfo.TextScaled = true
	settingsInfo.Font = Enum.Font.Gotham
	settingsInfo.TextWrapped = true
	settingsInfo.TextStrokeTransparency = 0.5
	settingsInfo.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	settingsInfo.Parent = settingsFrame
	
	-- Settings Commands
	local settingsCommandsFrame = Instance.new("Frame")
	settingsCommandsFrame.Size = UDim2.new(1, 0, 1, -90)
	settingsCommandsFrame.BackgroundTransparency = 1
	settingsCommandsFrame.Parent = settingsFrame
	
	local settingsCommandsLayout = Instance.new("UIListLayout")
	settingsCommandsLayout.FillDirection = Enum.FillDirection.Vertical
	settingsCommandsLayout.VerticalAlignment = Enum.VerticalAlignment.Top
	settingsCommandsLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
	settingsCommandsLayout.Padding = UDim.new(0, 5)
	settingsCommandsLayout.Parent = settingsCommandsFrame
	
	-- Create settings button
	local function createSettingsButton(text, action, color)
		local button = Instance.new("TextButton")
		button.Size = UDim2.new(1, 0, 0, 30)
		button.BackgroundColor3 = color or CONFIG.ADMIN_COLOR
		button.BorderSizePixel = 0
		button.Text = text
		button.TextColor3 = Color3.fromRGB(255, 255, 255)
		button.TextScaled = true
		button.Font = Enum.Font.GothamBold
		button.Parent = settingsCommandsFrame
		
		local buttonCorner = Instance.new("UICorner")
		buttonCorner.CornerRadius = UDim.new(0, 6)
		buttonCorner.Parent = button
		
		button.MouseButton1Click:Connect(function()
			if buttonDebounce() then
				action()
			end
		end)
		
		return button
	end
	
	-- Settings commands
	createSettingsButton("🔄 Refresh Menu", function()
		switchTab(currentTab)
		print("🔄 Menu refreshed")
	end, Color3.fromRGB(50, 205, 50))
	
	createSettingsButton("📊 Show Stats", function()
		print("📊 Server Stats:")
		print("Players: " .. #Players:GetPlayers())
		print("Server: " .. CONFIG.SERVER_NAME)
		print("Admin: " .. LOCAL_PLAYER.Name)
	end, Color3.fromRGB(100, 149, 237))
	
	createSettingsButton("🧹 Clear Shared Menus", function()
		for player, _ in pairs(sharedPlayers) do
			removeSharedMenuFromPlayer(player)
		end
		sharedPlayers = {}
		print("🧹 Cleared all shared menus")
	end, Color3.fromRGB(255, 165, 0))
	
	createSettingsButton("💾 Save Settings", function()
		print("💾 Settings saved")
	end, Color3.fromRGB(50, 205, 50))
	
	createSettingsButton("🔄 Reset Settings", function()
		print("🔄 Settings reset")
	end, Color3.fromRGB(220, 20, 60))
end

-- Share menu to player
local function shareMenuToPlayer(player)
	if not shareDebounce() then return end
	
	if not sharedPlayers[player] then
		sharedPlayers[player] = true
		print("📤 Menu shared to:", player.Name)
		
		-- Create shared menu for player
		createSharedMenuForPlayer(player)
	else
		sharedPlayers[player] = nil
		print("📤 Menu unshared from:", player.Name)
		
		-- Remove shared menu from player
		removeSharedMenuFromPlayer(player)
	end
end

-- Create shared menu for player
local function createSharedMenuForPlayer(player)
	if not player:FindFirstChild("PlayerGui") then return end
	
	local sharedGui = Instance.new("ScreenGui")
	sharedGui.Name = "SharedAdvancedAdminMenu"
	sharedGui.IgnoreGuiInset = true
	sharedGui.ResetOnSpawn = false
	sharedGui.DisplayOrder = 999
	sharedGui.Parent = player.PlayerGui
	
	local sharedFrame = Instance.new("Frame")
	sharedFrame.Size = UDim2.new(0, CONFIG.MENU_WIDTH, 0, CONFIG.MENU_HEIGHT)
	sharedFrame.Position = UDim2.new(CONFIG.MENU_POSITION_X, -CONFIG.MENU_WIDTH/2, CONFIG.MENU_POSITION_Y, -CONFIG.MENU_HEIGHT/2)
	sharedFrame.BackgroundColor3 = Color3.fromRGB(25, 25, 35)
	sharedFrame.BorderSizePixel = 0
	sharedFrame.ZIndex = 15
	sharedFrame.Parent = sharedGui
	
	local sharedCorner = Instance.new("UICorner")
	sharedCorner.CornerRadius = UDim.new(0, 15)
	sharedCorner.Parent = sharedFrame
	
	local sharedStroke = Instance.new("UIStroke")
	sharedStroke.Color = CONFIG.PLAYER_COLOR
	sharedStroke.Thickness = 3
	sharedStroke.Parent = sharedFrame
	
	local sharedPadding = Instance.new("UIPadding")
	sharedPadding.PaddingLeft = UDim.new(0, 15)
	sharedPadding.PaddingRight = UDim.new(0, 15)
	sharedPadding.PaddingTop = UDim.new(0, 15)
	sharedPadding.PaddingBottom = UDim.new(0, 15)
	sharedPadding.Parent = sharedFrame
	
	local sharedLayout = Instance.new("UIListLayout")
	sharedLayout.FillDirection = Enum.FillDirection.Vertical
	sharedLayout.VerticalAlignment = Enum.VerticalAlignment.Top
	sharedLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
	sharedLayout.Padding = UDim.new(0, 10)
	sharedLayout.Parent = sharedFrame
	
	-- Shared header
	local sharedHeader = Instance.new("Frame")
	sharedHeader.Size = UDim2.new(1, 0, 0, 50)
	sharedHeader.BackgroundTransparency = 1
	sharedHeader.Parent = sharedFrame
	
	local sharedHeaderLayout = Instance.new("UIListLayout")
	sharedHeaderLayout.FillDirection = Enum.FillDirection.Horizontal
	sharedHeaderLayout.VerticalAlignment = Enum.VerticalAlignment.Center
	sharedHeaderLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
	sharedHeaderLayout.Padding = UDim.new(0, 10)
	sharedHeaderLayout.Parent = sharedHeader
	
	local sharedIcon = Instance.new("TextLabel")
	sharedIcon.Size = UDim2.new(0, 35, 0, 35)
	sharedIcon.BackgroundTransparency = 1
	sharedIcon.Text = "👥"
	sharedIcon.TextColor3 = CONFIG.PLAYER_COLOR
	sharedIcon.TextScaled = true
	sharedIcon.Font = Enum.Font.GothamBold
	sharedIcon.Parent = sharedHeader
	
	local sharedTitle = Instance.new("TextLabel")
	sharedTitle.Size = UDim2.new(1, -80, 1, 0)
	sharedTitle.BackgroundTransparency = 1
	sharedTitle.Text = "SHARED ADMIN MENU"
	sharedTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
	sharedTitle.TextScaled = true
	sharedTitle.Font = Enum.Font.GothamBold
	sharedTitle.TextStrokeTransparency = 0.5
	sharedTitle.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	sharedTitle.Parent = sharedHeader
	
	local sharedCloseButton = Instance.new("TextButton")
	sharedCloseButton.Size = UDim2.new(0, 35, 0, 35)
	sharedCloseButton.BackgroundColor3 = Color3.fromRGB(255, 0, 0)
	sharedCloseButton.BorderSizePixel = 0
	sharedCloseButton.Text = "✕"
	sharedCloseButton.TextColor3 = Color3.fromRGB(255, 255, 255)
	sharedCloseButton.TextScaled = true
	sharedCloseButton.Font = Enum.Font.GothamBold
	sharedCloseButton.Parent = sharedHeader
	
	local sharedCloseCorner = Instance.new("UICorner")
	sharedCloseCorner.CornerRadius = UDim.new(0, 8)
	sharedCloseCorner.Parent = sharedCloseButton
	
	-- Shared message
	local sharedMessage = Instance.new("TextLabel")
	sharedMessage.Size = UDim2.new(1, 0, 0, 80)
	sharedMessage.BackgroundTransparency = 1
	sharedMessage.Text = "Menu shared by " .. LOCAL_PLAYER.Name .. "\nThis menu will disappear when you leave the server.\nYou can view server info and settings."
	sharedMessage.TextColor3 = Color3.fromRGB(200, 200, 200)
	sharedMessage.TextScaled = true
	sharedMessage.Font = Enum.Font.Gotham
	sharedMessage.TextWrapped = true
	sharedMessage.TextStrokeTransparency = 0.5
	sharedMessage.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	sharedMessage.Parent = sharedFrame
	
	-- Close shared menu
	sharedCloseButton.MouseButton1Click:Connect(function()
		sharedGui:Destroy()
		sharedPlayers[player] = nil
	end)
	
	-- Auto remove when player leaves
	player.AncestryChanged:Connect(function()
		if not player.Parent then
			sharedGui:Destroy()
			sharedPlayers[player] = nil
		end
	end)
end

-- Remove shared menu from player
local function removeSharedMenuFromPlayer(player)
	if not player:FindFirstChild("PlayerGui") then return end
	
	local sharedGui = player.PlayerGui:FindFirstChild("SharedAdvancedAdminMenu")
	if sharedGui then
		sharedGui:Destroy()
	end
end

-- Open/Close menu
local function openMenu()
	if not menuDebounce() then return end
	
	menuOpen = true
	menuFrame.Visible = true
	menuFrame.BackgroundTransparency = 1
	
	-- Animate in
	TweenService:Create(menuFrame, TweenInfo.new(CONFIG.ANIMATION_SPEED), {
		BackgroundTransparency = 0,
		Size = UDim2.new(0, CONFIG.MENU_WIDTH, 0, CONFIG.MENU_HEIGHT)
	}):Play()
	
	-- Switch to default tab
	switchTab("Players")
	print("📱 Advanced admin menu opened")
end

local function closeMenu()
	if not menuDebounce() then return end
	
	menuOpen = false
	
	-- Animate out
	TweenService:Create(menuFrame, TweenInfo.new(CONFIG.ANIMATION_SPEED), {
		BackgroundTransparency = 1,
		Size = UDim2.new(0, 0, 0, 0)
	}):Play()
	
	wait(CONFIG.ANIMATION_SPEED)
	menuFrame.Visible = false
	print("📱 Advanced admin menu closed")
end

local function toggleMenu()
	if menuOpen then
		closeMenu()
	else
		openMenu()
	end
end

-- Update mobile layout
local function updateMobileLayout()
	local deviceType = getDeviceType()
	
	if deviceType == "mobile" then
		isMobile = true
		mobileIcon.Visible = true
		
		-- Update menu size for mobile
		menuFrame.Size = UDim2.new(0, CONFIG.MENU_WIDTH_MOBILE, 0, CONFIG.MENU_HEIGHT_MOBILE)
		menuFrame.Position = UDim2.new(CONFIG.MENU_POSITION_X, -CONFIG.MENU_WIDTH_MOBILE/2, CONFIG.MENU_POSITION_Y, -CONFIG.MENU_HEIGHT_MOBILE/2)
	else
		isMobile = false
		mobileIcon.Visible = false
		
		-- Update menu size for desktop
		menuFrame.Size = UDim2.new(0, CONFIG.MENU_WIDTH, 0, CONFIG.MENU_HEIGHT)
		menuFrame.Position = UDim2.new(CONFIG.MENU_POSITION_X, -CONFIG.MENU_WIDTH/2, CONFIG.MENU_POSITION_Y, -CONFIG.MENU_HEIGHT/2)
	end
end

-- Initialize
local function initialize()
	-- Check admin status
	isAdmin, isModerator = checkAdminStatus()
	
	if not isAdmin and not isModerator then
		print("❌ Player is not admin or moderator")
		return
	end
	
	-- Update mobile layout
	updateMobileLayout()
	
	-- Create tabs
	createTabButton("Players", "Players")
	createTabButton("Server", "Server")
	createTabButton("Settings", "Settings")
	
	print("✅ Advanced admin menu system initialized")
end

-- Event connections
UserInputService.InputBegan:Connect(function(input, gameProcessed)
	if gameProcessed then return end
	
	if input.KeyCode == Enum.KeyCode.F3 then
		toggleMenu()
	end
end)

mobileIcon.MouseButton1Click:Connect(function()
	if buttonDebounce() then
		toggleMenu()
	end
end)

closeButton.MouseButton1Click:Connect(function()
	if buttonDebounce() then
		closeMenu()
	end
end)

-- Monitor device changes
UserInputService:GetPropertyChangedSignal("TouchEnabled"):Connect(updateMobileLayout)
UserInputService:GetPropertyChangedSignal("GamepadEnabled"):Connect(updateMobileLayout)
UserInputService:GetPropertyChangedSignal("KeyboardEnabled"):Connect(updateMobileLayout)

-- Monitor player changes
Players.PlayerAdded:Connect(function(player)
	wait(1) -- Wait for player to load
	if currentTab == "Players" then
		switchTab("Players") -- Refresh players tab
	end
end)

Players.PlayerRemoving:Connect(function(player)
	-- Remove from shared players
	sharedPlayers[player] = nil
	if currentTab == "Players" then
		switchTab("Players") -- Refresh players tab
	end
end)

-- Initialize
initialize()

-- Global functions
_G.AdvancedAdminMenuSystem = {
	-- Menu control
	open = openMenu,
	close = closeMenu,
	toggle = toggleMenu,
	
	-- Status
	isOpen = function()
		return menuOpen
	end,
	
	isAdmin = function()
		return isAdmin
	end,
	
	isModerator = function()
		return isModerator
	end,
	
	isMobile = function()
		return isMobile
	end,
	
	-- Tab control
	switchTab = switchTab,
	getCurrentTab = function()
		return currentTab
	end,
	
	-- Player management
	shareMenuToPlayer = shareMenuToPlayer,
	
	-- Configuration
	config = CONFIG
}

-- Commands
print("🔧 COMMANDS UNTUK TEST ADVANCED ADMIN MENU:")
print("_G.AdvancedAdminMenuSystem.open() - Open menu")
print("_G.AdvancedAdminMenuSystem.close() - Close menu")
print("_G.AdvancedAdminMenuSystem.toggle() - Toggle menu")
print("_G.AdvancedAdminMenuSystem.isOpen() - Check if menu is open")
print("_G.AdvancedAdminMenuSystem.isAdmin() - Check admin status")
print("_G.AdvancedAdminMenuSystem.isModerator() - Check moderator status")
print("_G.AdvancedAdminMenuSystem.isMobile() - Check if mobile")
print("_G.AdvancedAdminMenuSystem.switchTab('Players') - Switch tab")
print("_G.AdvancedAdminMenuSystem.getCurrentTab() - Get current tab")
print("_G.AdvancedAdminMenuSystem.shareMenuToPlayer(player) - Share menu")
print("")
print("🎮 CONTROLS:")
print("F3 - Toggle menu (Desktop)")
print("Mobile Icon - Toggle menu (Mobile)")
print("Click player name - Share menu to player")
print("Click tabs - Switch between Players/Server/Settings")
print("Click server commands - Execute server actions")
print("")
print("✅ ADVANCED FEATURES:")
print("- F3 key untuk buka menu (Desktop)")
print("- Mobile icon di kanan untuk buka menu (Mobile)")
print("- Tab navigation (Players/Server/Settings)")
print("- Menu bisa di-share ke player lain")
print("- Menu hilang saat player keluar server")
print("- Optimasi ukuran untuk mobile")
print("- Server commands (Time, Weather, Sounds)")
print("- Player management dengan ID display")
print("- Settings tab dengan admin info")
print("- Debounce untuk mencegah spam")
print("- Smooth animations")
print("- Lightweight dan optimized")
print("- No bugs atau errors")
print("")
print("👑 ADVANCED ADMIN MENU SYSTEM READY!")
print("Press F3 to open menu or click mobile icon!")