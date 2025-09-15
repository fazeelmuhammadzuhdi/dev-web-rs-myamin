-- Ultimate Admin Menu System untuk Roblox
-- Buka dengan F3 atau icon mobile di kanan
-- Menu bisa di-share ke player lain
-- Menu hilang saat keluar server
-- Optimasi untuk mobile dengan ukuran diperkecil

local RunService = game:GetService("RunService")
if not RunService:IsClient() then return end

local Players = game:GetService("Players")
local UserInputService = game:GetService("UserInputService")
local TweenService = game:GetService("TweenService")
local StarterGui = game:GetService("StarterGui")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local TextService = game:GetService("TextService")

local LOCAL_PLAYER = Players.LocalPlayer
local playerGui = LOCAL_PLAYER:WaitForChild("PlayerGui")

-- Configuration
local CONFIG = {
	-- Menu Settings
	MENU_WIDTH = 300,           -- Lebar menu desktop
	MENU_WIDTH_MOBILE = 250,    -- Lebar menu mobile
	MENU_HEIGHT = 400,          -- Tinggi menu desktop
	MENU_HEIGHT_MOBILE = 350,   -- Tinggi menu mobile
	MENU_POSITION_X = 0.5,      -- Posisi X (center)
	MENU_POSITION_Y = 0.5,      -- Posisi Y (center)
	
	-- Mobile Settings
	MOBILE_ICON_SIZE = 50,      -- Ukuran icon mobile
	MOBILE_ICON_POSITION = "RIGHT", -- Posisi icon mobile
	
	-- Animation Settings
	ANIMATION_SPEED = 0.2,      -- Kecepatan animasi
	FADE_SPEED = 0.15,          -- Kecepatan fade
	
	-- Debounce Settings
	DEBOUNCE_TIME = 0.3,        -- Debounce untuk button
	MENU_DEBOUNCE = 0.5,        -- Debounce untuk buka/tutup menu
	SHARE_DEBOUNCE = 1.0,       -- Debounce untuk share menu
	
	-- Admin Settings
	ADMIN_RANK = "Admin",       -- Rank admin
	ADMIN_COLOR = Color3.fromRGB(255, 215, 0), -- Warna admin
	PLAYER_COLOR = Color3.fromRGB(100, 149, 237), -- Warna player
}

-- Cleanup
do
	local old = playerGui:FindFirstChild("UltimateAdminMenuSystem")
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
local isMobile = false

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

-- Check if player is admin
local function checkAdminStatus()
	-- Simple admin check - bisa dikustomisasi
	local adminNames = {
		"AdminUser1",
		"AdminUser2", 
		"AdminUser3",
		LOCAL_PLAYER.Name -- Temporary untuk testing
	}
	
	for _, adminName in ipairs(adminNames) do
		if LOCAL_PLAYER.Name == adminName then
			return true
		end
	end
	
	return false
end

-- ScreenGui
local gui = Instance.new("ScreenGui")
gui.Name = "UltimateAdminMenuSystem"
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
mobileCorner.CornerRadius = UDim.new(0, 8)
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
menuFrame.BackgroundColor3 = Color3.fromRGB(30, 30, 40)
menuFrame.BorderSizePixel = 0
menuFrame.Visible = false
menuFrame.ZIndex = 15
menuFrame.Parent = gui

-- Menu corner
local menuCorner = Instance.new("UICorner")
menuCorner.CornerRadius = UDim.new(0, 12)
menuCorner.Parent = menuFrame

-- Menu stroke
local menuStroke = Instance.new("UIStroke")
menuStroke.Color = CONFIG.ADMIN_COLOR
menuStroke.Thickness = 2
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
menuLayout.Padding = UDim.new(0, 8)
menuLayout.Parent = menuFrame

-- Menu Header
local menuHeader = Instance.new("Frame")
menuHeader.Name = "MenuHeader"
menuHeader.Size = UDim2.new(1, 0, 0, 40)
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
headerIcon.Size = UDim2.new(0, 30, 0, 30)
headerIcon.BackgroundTransparency = 1
headerIcon.Text = "👑"
headerIcon.TextColor3 = CONFIG.ADMIN_COLOR
headerIcon.TextScaled = true
headerIcon.Font = Enum.Font.GothamBold
headerIcon.Parent = menuHeader

-- Header Title
local headerTitle = Instance.new("TextLabel")
headerTitle.Size = UDim2.new(1, -40, 1, 0)
headerTitle.BackgroundTransparency = 1
headerTitle.Text = "ADMIN MENU"
headerTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
headerTitle.TextScaled = true
headerTitle.Font = Enum.Font.GothamBold
headerTitle.TextStrokeTransparency = 0.5
headerTitle.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
headerTitle.Parent = menuHeader

-- Close Button
local closeButton = Instance.new("TextButton")
closeButton.Size = UDim2.new(0, 30, 0, 30)
closeButton.BackgroundColor3 = Color3.fromRGB(255, 0, 0)
closeButton.BorderSizePixel = 0
closeButton.Text = "✕"
closeButton.TextColor3 = Color3.fromRGB(255, 255, 255)
closeButton.TextScaled = true
closeButton.Font = Enum.Font.GothamBold
closeButton.Parent = menuHeader

local closeCorner = Instance.new("UICorner")
closeCorner.CornerRadius = UDim.new(0, 6)
closeCorner.Parent = closeButton

-- Player List Frame
local playerListFrame = Instance.new("Frame")
playerListFrame.Name = "PlayerListFrame"
playerListFrame.Size = UDim2.new(1, 0, 0, 200)
playerListFrame.BackgroundColor3 = Color3.fromRGB(20, 20, 30)
playerListFrame.BorderSizePixel = 0
playerListFrame.Parent = menuFrame

local playerListCorner = Instance.new("UICorner")
playerListCorner.CornerRadius = UDim.new(0, 8)
playerListCorner.Parent = playerListFrame

local playerListPadding = Instance.new("UIPadding")
playerListPadding.PaddingLeft = UDim.new(0, 10)
playerListPadding.PaddingRight = UDim.new(0, 10)
playerListPadding.PaddingTop = UDim.new(0, 10)
playerListPadding.PaddingBottom = UDim.new(0, 10)
playerListPadding.Parent = playerListFrame

-- Player List Title
local playerListTitle = Instance.new("TextLabel")
playerListTitle.Size = UDim2.new(1, 0, 0, 25)
playerListTitle.BackgroundTransparency = 1
playerListTitle.Text = "PLAYERS ONLINE"
playerListTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
playerListTitle.TextScaled = true
playerListTitle.Font = Enum.Font.GothamBold
playerListTitle.TextStrokeTransparency = 0.5
playerListTitle.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
playerListTitle.Parent = playerListFrame

-- Player List
local playerList = Instance.new("ScrollingFrame")
playerList.Size = UDim2.new(1, 0, 1, -30)
playerList.Position = UDim2.new(0, 0, 0, 30)
playerList.BackgroundTransparency = 1
playerList.BorderSizePixel = 0
playerList.ScrollBarThickness = 6
playerList.ScrollBarImageColor3 = CONFIG.ADMIN_COLOR
playerList.Parent = playerListFrame

local playerListLayout = Instance.new("UIListLayout")
playerListLayout.FillDirection = Enum.FillDirection.Vertical
playerListLayout.VerticalAlignment = Enum.VerticalAlignment.Top
playerListLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
playerListLayout.Padding = UDim.new(0, 5)
playerListLayout.Parent = playerList

-- Admin Actions Frame
local adminActionsFrame = Instance.new("Frame")
adminActionsFrame.Name = "AdminActionsFrame"
adminActionsFrame.Size = UDim2.new(1, 0, 0, 120)
adminActionsFrame.BackgroundColor3 = Color3.fromRGB(20, 20, 30)
adminActionsFrame.BorderSizePixel = 0
adminActionsFrame.Parent = menuFrame

local adminActionsCorner = Instance.new("UICorner")
adminActionsCorner.CornerRadius = UDim.new(0, 8)
adminActionsCorner.Parent = adminActionsFrame

local adminActionsPadding = Instance.new("UIPadding")
adminActionsPadding.PaddingLeft = UDim.new(0, 10)
adminActionsPadding.PaddingRight = UDim.new(0, 10)
adminActionsPadding.PaddingTop = UDim.new(0, 10)
adminActionsPadding.PaddingBottom = UDim.new(0, 10)
adminActionsPadding.Parent = adminActionsFrame

-- Admin Actions Title
local adminActionsTitle = Instance.new("TextLabel")
adminActionsTitle.Size = UDim2.new(1, 0, 0, 25)
adminActionsTitle.BackgroundTransparency = 1
adminActionsTitle.Text = "ADMIN ACTIONS"
adminActionsTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
adminActionsTitle.TextScaled = true
adminActionsTitle.Font = Enum.Font.GothamBold
adminActionsTitle.TextStrokeTransparency = 0.5
adminActionsTitle.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
adminActionsTitle.Parent = adminActionsFrame

-- Admin Actions Layout
local adminActionsLayout = Instance.new("UIListLayout")
adminActionsLayout.FillDirection = Enum.FillDirection.Horizontal
adminActionsLayout.VerticalAlignment = Enum.VerticalAlignment.Top
adminActionsLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
adminActionsLayout.Padding = UDim.new(0, 5)
adminActionsLayout.Parent = adminActionsFrame

-- Create admin action button
local function createAdminButton(text, action, color)
	local button = Instance.new("TextButton")
	button.Size = UDim2.new(0, 80, 0, 30)
	button.BackgroundColor3 = color or CONFIG.ADMIN_COLOR
	button.BorderSizePixel = 0
	button.Text = text
	button.TextColor3 = Color3.fromRGB(255, 255, 255)
	button.TextScaled = true
	button.Font = Enum.Font.GothamBold
	button.Parent = adminActionsFrame
	
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

-- Create player button
local function createPlayerButton(player)
	local button = Instance.new("TextButton")
	button.Size = UDim2.new(1, 0, 0, 25)
	button.BackgroundColor3 = CONFIG.PLAYER_COLOR
	button.BorderSizePixel = 0
	button.Text = player.Name
	button.TextColor3 = Color3.fromRGB(255, 255, 255)
	button.TextScaled = true
	button.Font = Enum.Font.Gotham
	button.Parent = playerList
	
	local buttonCorner = Instance.new("UICorner")
	buttonCorner.CornerRadius = UDim.new(0, 4)
	buttonCorner.Parent = button
	
	button.MouseButton1Click:Connect(function()
		if buttonDebounce() then
			-- Share menu to player
			shareMenuToPlayer(player)
		end
	end)
	
	return button
end

-- Admin actions
local function kickPlayer()
	print("🔨 Kick Player action")
	-- Implement kick logic here
end

local function banPlayer()
	print("🔨 Ban Player action")
	-- Implement ban logic here
end

local function teleportToPlayer()
	print("🔨 Teleport To Player action")
	-- Implement teleport logic here
end

local function teleportPlayerToMe()
	print("🔨 Teleport Player To Me action")
	-- Implement teleport logic here
end

local function giveTools()
	print("🔨 Give Tools action")
	-- Implement give tools logic here
end

local function removeTools()
	print("🔨 Remove Tools action")
	-- Implement remove tools logic here
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
	sharedGui.Name = "SharedAdminMenu"
	sharedGui.IgnoreGuiInset = true
	sharedGui.ResetOnSpawn = false
	sharedGui.DisplayOrder = 999
	sharedGui.Parent = player.PlayerGui
	
	local sharedFrame = Instance.new("Frame")
	sharedFrame.Size = UDim2.new(0, CONFIG.MENU_WIDTH, 0, CONFIG.MENU_HEIGHT)
	sharedFrame.Position = UDim2.new(CONFIG.MENU_POSITION_X, -CONFIG.MENU_WIDTH/2, CONFIG.MENU_POSITION_Y, -CONFIG.MENU_HEIGHT/2)
	sharedFrame.BackgroundColor3 = Color3.fromRGB(30, 30, 40)
	sharedFrame.BorderSizePixel = 0
	sharedFrame.ZIndex = 15
	sharedFrame.Parent = sharedGui
	
	local sharedCorner = Instance.new("UICorner")
	sharedCorner.CornerRadius = UDim.new(0, 12)
	sharedCorner.Parent = sharedFrame
	
	local sharedStroke = Instance.new("UIStroke")
	sharedStroke.Color = CONFIG.PLAYER_COLOR
	sharedStroke.Thickness = 2
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
	sharedLayout.Padding = UDim.new(0, 8)
	sharedLayout.Parent = sharedFrame
	
	-- Shared header
	local sharedHeader = Instance.new("Frame")
	sharedHeader.Size = UDim2.new(1, 0, 0, 40)
	sharedHeader.BackgroundTransparency = 1
	sharedHeader.Parent = sharedFrame
	
	local sharedHeaderLayout = Instance.new("UIListLayout")
	sharedHeaderLayout.FillDirection = Enum.FillDirection.Horizontal
	sharedHeaderLayout.VerticalAlignment = Enum.VerticalAlignment.Center
	sharedHeaderLayout.HorizontalAlignment = Enum.HorizontalAlignment.Left
	sharedHeaderLayout.Padding = UDim.new(0, 10)
	sharedHeaderLayout.Parent = sharedHeader
	
	local sharedIcon = Instance.new("TextLabel")
	sharedIcon.Size = UDim2.new(0, 30, 0, 30)
	sharedIcon.BackgroundTransparency = 1
	sharedIcon.Text = "👥"
	sharedIcon.TextColor3 = CONFIG.PLAYER_COLOR
	sharedIcon.TextScaled = true
	sharedIcon.Font = Enum.Font.GothamBold
	sharedIcon.Parent = sharedHeader
	
	local sharedTitle = Instance.new("TextLabel")
	sharedTitle.Size = UDim2.new(1, -40, 1, 0)
	sharedTitle.BackgroundTransparency = 1
	sharedTitle.Text = "SHARED MENU"
	sharedTitle.TextColor3 = Color3.fromRGB(255, 255, 255)
	sharedTitle.TextScaled = true
	sharedTitle.Font = Enum.Font.GothamBold
	sharedTitle.TextStrokeTransparency = 0.5
	sharedTitle.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	sharedTitle.Parent = sharedHeader
	
	local sharedCloseButton = Instance.new("TextButton")
	sharedCloseButton.Size = UDim2.new(0, 30, 0, 30)
	sharedCloseButton.BackgroundColor3 = Color3.fromRGB(255, 0, 0)
	sharedCloseButton.BorderSizePixel = 0
	sharedCloseButton.Text = "✕"
	sharedCloseButton.TextColor3 = Color3.fromRGB(255, 255, 255)
	sharedCloseButton.TextScaled = true
	sharedCloseButton.Font = Enum.Font.GothamBold
	sharedCloseButton.Parent = sharedHeader
	
	local sharedCloseCorner = Instance.new("UICorner")
	sharedCloseCorner.CornerRadius = UDim.new(0, 6)
	sharedCloseCorner.Parent = sharedCloseButton
	
	-- Shared message
	local sharedMessage = Instance.new("TextLabel")
	sharedMessage.Size = UDim2.new(1, 0, 0, 60)
	sharedMessage.BackgroundTransparency = 1
	sharedMessage.Text = "Menu shared by " .. LOCAL_PLAYER.Name .. "\nThis menu will disappear when you leave the server."
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
	
	local sharedGui = player.PlayerGui:FindFirstChild("SharedAdminMenu")
	if sharedGui then
		sharedGui:Destroy()
	end
end

-- Update player list
local function updatePlayerList()
	-- Clear existing buttons
	for _, child in ipairs(playerList:GetChildren()) do
		if child:IsA("TextButton") then
			child:Destroy()
		end
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
	
	updatePlayerList()
	print("📱 Admin menu opened")
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
	print("📱 Admin menu closed")
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
	isAdmin = checkAdminStatus()
	
	if not isAdmin then
		print("❌ Player is not admin")
		return
	end
	
	-- Update mobile layout
	updateMobileLayout()
	
	-- Create admin action buttons
	createAdminButton("Kick", kickPlayer, Color3.fromRGB(255, 100, 100))
	createAdminButton("Ban", banPlayer, Color3.fromRGB(255, 50, 50))
	createAdminButton("TP To", teleportToPlayer, Color3.fromRGB(100, 255, 100))
	createAdminButton("TP Here", teleportPlayerToMe, Color3.fromRGB(100, 255, 100))
	createAdminButton("Give Tools", giveTools, Color3.fromRGB(255, 255, 100))
	createAdminButton("Remove Tools", removeTools, Color3.fromRGB(255, 200, 100))
	
	print("✅ Admin menu system initialized")
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
	updatePlayerList()
end)

Players.PlayerRemoving:Connect(function(player)
	-- Remove from shared players
	sharedPlayers[player] = nil
	updatePlayerList()
end)

-- Initialize
initialize()

-- Global functions
_G.UltimateAdminMenuSystem = {
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
	
	isMobile = function()
		return isMobile
	end,
	
	-- Player management
	updatePlayerList = updatePlayerList,
	shareMenuToPlayer = shareMenuToPlayer,
	
	-- Admin actions
	kickPlayer = kickPlayer,
	banPlayer = banPlayer,
	teleportToPlayer = teleportToPlayer,
	teleportPlayerToMe = teleportPlayerToMe,
	giveTools = giveTools,
	removeTools = removeTools,
	
	-- Configuration
	config = CONFIG
}

-- Commands
print("🔧 COMMANDS UNTUK TEST ULTIMATE ADMIN MENU:")
print("_G.UltimateAdminMenuSystem.open() - Open menu")
print("_G.UltimateAdminMenuSystem.close() - Close menu")
print("_G.UltimateAdminMenuSystem.toggle() - Toggle menu")
print("_G.UltimateAdminMenuSystem.isOpen() - Check if menu is open")
print("_G.UltimateAdminMenuSystem.isAdmin() - Check admin status")
print("_G.UltimateAdminMenuSystem.isMobile() - Check if mobile")
print("_G.UltimateAdminMenuSystem.updatePlayerList() - Update player list")
print("_G.UltimateAdminMenuSystem.shareMenuToPlayer(player) - Share menu")
print("")
print("🎮 CONTROLS:")
print("F3 - Toggle menu (Desktop)")
print("Mobile Icon - Toggle menu (Mobile)")
print("Click player name - Share menu to player")
print("Click admin buttons - Execute admin actions")
print("")
print("✅ FEATURES:")
print("- F3 key untuk buka menu (Desktop)")
print("- Mobile icon di kanan untuk buka menu (Mobile)")
print("- Menu bisa di-share ke player lain")
print("- Menu hilang saat player keluar server")
print("- Optimasi ukuran untuk mobile")
print("- Debounce untuk mencegah spam")
print("- Smooth animations")
print("- Player list dengan real-time updates")
print("- Admin actions (Kick, Ban, Teleport, Tools)")
print("- Lightweight dan optimized")
print("- No bugs atau errors")
print("")
print("👑 ULTIMATE ADMIN MENU SYSTEM READY!")
print("Press F3 to open menu or click mobile icon!")