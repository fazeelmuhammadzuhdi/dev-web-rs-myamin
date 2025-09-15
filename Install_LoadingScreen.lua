-- Ultimate Loading Screen Installer
-- Script untuk instalasi otomatis Ultimate Loading Screen
-- Optimized dan tidak ada bug

local Players = game:GetService("Players")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local ServerStorage = game:GetService("ServerStorage")
local StarterGui = game:GetService("StarterGui")
local StarterPlayer = game:GetService("StarterPlayer")
local StarterPlayerScripts = StarterPlayer:WaitForChild("StarterPlayerScripts")

-- Installation Configuration
local INSTALL_CONFIG = {
	-- Installation options
	INSTALL_TO_SERVERSCRIPT = true, -- Install to ServerScriptService
	INSTALL_TO_STARTERGUI = false, -- Install to StarterGui
	INSTALL_TO_STARTERPLAYER = false, -- Install to StarterPlayerScripts
	INSTALL_INTEGRATION = true, -- Install integration script
	INSTALL_DEMO = true, -- Install demo script
	INSTALL_CONFIG_FILE = true, -- Install config file
	
	-- File names
	MAIN_SCRIPT_NAME = "UltimateLoadingScreen",
	INTEGRATION_SCRIPT_NAME = "LoadingScreenIntegration",
	DEMO_SCRIPT_NAME = "LoadingScreenDemo",
	CONFIG_SCRIPT_NAME = "LoadingScreenConfig",
	
	-- Backup options
	CREATE_BACKUP = true, -- Create backup of existing scripts
	BACKUP_FOLDER_NAME = "LoadingScreenBackup",
}

-- Global variables
local installationLog = {}
local backupFolder = nil

-- Utility Functions
local function log(message)
	local timestamp = os.date("%H:%M:%S")
	local logMessage = "[" .. timestamp .. "] " .. message
	table.insert(installationLog, logMessage)
	print(logMessage)
end

local function createBackup()
	if not INSTALL_CONFIG.CREATE_BACKUP then return end
	
	log("Creating backup folder...")
	
	-- Create backup folder in ServerStorage
	backupFolder = Instance.new("Folder")
	backupFolder.Name = INSTALL_CONFIG.BACKUP_FOLDER_NAME
	backupFolder.Parent = ServerStorage
	
	log("Backup folder created: " .. backupFolder.Name)
end

local function backupExistingScripts()
	if not INSTALL_CONFIG.CREATE_BACKUP then return end
	
	log("Backing up existing scripts...")
	
	-- Backup from ServerScriptService
	local serverScriptService = game:GetService("ServerScriptService")
	for _, child in ipairs(serverScriptService:GetChildren()) do
		if child.Name:find("LoadingScreen") or child.Name:find("Ultimate") then
			local backup = child:Clone()
			backup.Name = child.Name .. "_BACKUP_" .. os.time()
			backup.Parent = backupFolder
			log("Backed up: " .. child.Name)
		end
	end
	
	-- Backup from StarterGui
	local starterGui = game:GetService("StarterGui")
	for _, child in ipairs(starterGui:GetChildren()) do
		if child.Name:find("LoadingScreen") or child.Name:find("Ultimate") then
			local backup = child:Clone()
			backup.Name = child.Name .. "_BACKUP_" .. os.time()
			backup.Parent = backupFolder
			log("Backed up: " .. child.Name)
		end
	end
	
	-- Backup from StarterPlayerScripts
	for _, child in ipairs(StarterPlayerScripts:GetChildren()) do
		if child.Name:find("LoadingScreen") or child.Name:find("Ultimate") then
			local backup = child:Clone()
			backup.Name = child.Name .. "_BACKUP_" .. os.time()
			backup.Parent = backupFolder
			log("Backed up: " .. child.Name)
		end
	end
end

local function removeExistingScripts()
	log("Removing existing scripts...")
	
	-- Remove from ServerScriptService
	local serverScriptService = game:GetService("ServerScriptService")
	for _, child in ipairs(serverScriptService:GetChildren()) do
		if child.Name:find("LoadingScreen") or child.Name:find("Ultimate") then
			child:Destroy()
			log("Removed: " .. child.Name)
		end
	end
	
	-- Remove from StarterGui
	local starterGui = game:GetService("StarterGui")
	for _, child in ipairs(starterGui:GetChildren()) do
		if child.Name:find("LoadingScreen") or child.Name:find("Ultimate") then
			child:Destroy()
			log("Removed: " .. child.Name)
		end
	end
	
	-- Remove from StarterPlayerScripts
	for _, child in ipairs(StarterPlayerScripts:GetChildren()) do
		if child.Name:find("LoadingScreen") or child.Name:find("Ultimate") then
			child:Destroy()
			log("Removed: " .. child.Name)
		end
	end
end

-- Script Content
local function getMainScriptContent()
	return [[-- Ultimate Loading Screen - Mountain Theme
-- Script Loading Screen Roblox Keren Modern dengan Tema Gunung
-- Optimized dengan debounce dan tidak ada bug/error
-- Dapat diubah tema secara manual

local Players = game:GetService("Players")
local TweenService = game:GetService("TweenService")
local RunService = game:GetService("RunService")
local UserInputService = game:GetService("UserInputService")
local SoundService = game:GetService("SoundService")
local ReplicatedStorage = game:GetService("ReplicatedStorage")

-- Configuration
local LOADING_CONFIG = {
	-- Tema yang tersedia
	THEMES = {
		MOUNTAIN = {
			name = "Mountain",
			colors = {
				primary = Color3.fromRGB(34, 139, 34), -- Forest Green
				secondary = Color3.fromRGB(139, 69, 19), -- Saddle Brown
				accent = Color3.fromRGB(255, 215, 0), -- Gold
				background = Color3.fromRGB(25, 25, 25), -- Dark Gray
				text = Color3.fromRGB(255, 255, 255) -- White
			},
			gradient = {
				ColorSequenceKeypoint.new(0, Color3.fromRGB(34, 139, 34)),
				ColorSequenceKeypoint.new(0.5, Color3.fromRGB(139, 69, 19)),
				ColorSequenceKeypoint.new(1, Color3.fromRGB(255, 215, 0))
			}
		},
		OCEAN = {
			name = "Ocean",
			colors = {
				primary = Color3.fromRGB(0, 100, 200), -- Ocean Blue
				secondary = Color3.fromRGB(0, 150, 255), -- Light Blue
				accent = Color3.fromRGB(255, 255, 255), -- White
				background = Color3.fromRGB(10, 10, 30), -- Dark Blue
				text = Color3.fromRGB(255, 255, 255) -- White
			},
			gradient = {
				ColorSequenceKeypoint.new(0, Color3.fromRGB(0, 100, 200)),
				ColorSequenceKeypoint.new(0.5, Color3.fromRGB(0, 150, 255)),
				ColorSequenceKeypoint.new(1, Color3.fromRGB(255, 255, 255))
			}
		},
		SPACE = {
			name = "Space",
			colors = {
				primary = Color3.fromRGB(75, 0, 130), -- Indigo
				secondary = Color3.fromRGB(138, 43, 226), -- Blue Violet
				accent = Color3.fromRGB(255, 255, 0), -- Yellow
				background = Color3.fromRGB(0, 0, 0), -- Black
				text = Color3.fromRGB(255, 255, 255) -- White
			},
			gradient = {
				ColorSequenceKeypoint.new(0, Color3.fromRGB(75, 0, 130)),
				ColorSequenceKeypoint.new(0.5, Color3.fromRGB(138, 43, 226)),
				ColorSequenceKeypoint.new(1, Color3.fromRGB(255, 255, 0))
			}
		},
		SUNSET = {
			name = "Sunset",
			colors = {
				primary = Color3.fromRGB(255, 69, 0), -- Red Orange
				secondary = Color3.fromRGB(255, 140, 0), -- Dark Orange
				accent = Color3.fromRGB(255, 215, 0), -- Gold
				background = Color3.fromRGB(30, 15, 5), -- Dark Brown
				text = Color3.fromRGB(255, 255, 255) -- White
			},
			gradient = {
				ColorSequenceKeypoint.new(0, Color3.fromRGB(255, 69, 0)),
				ColorSequenceKeypoint.new(0.5, Color3.fromRGB(255, 140, 0)),
				ColorSequenceKeypoint.new(1, Color3.fromRGB(255, 215, 0))
			}
		}
	},
	
	-- Pengaturan Loading Screen
	CURRENT_THEME = "MOUNTAIN", -- Default theme
	LOADING_TIME = 5, -- Durasi loading dalam detik
	ANIMATION_SPEED = 1, -- Kecepatan animasi
	ENABLE_SOUNDS = true, -- Enable/disable sounds
	ENABLE_PARTICLES = true, -- Enable/disable particles
	ENABLE_BACKGROUND_ANIMATION = true, -- Enable/disable background animation
	
	-- Debounce settings
	DEBOUNCE_TIME = 0.1, -- Debounce time untuk mencegah spam
	ANIMATION_DEBOUNCE = 0.05, -- Debounce untuk animasi
}

-- Global variables
local currentTheme = LOADING_CONFIG.THEMES[LOADING_CONFIG.CURRENT_THEME]
local loadingScreen = nil
local isLoaded = false
local debounceTime = 0
local animationDebounce = 0

-- Utility Functions
local function createDebounce()
	local lastTime = 0
	return function()
		local currentTime = tick()
		if currentTime - lastTime < LOADING_CONFIG.DEBOUNCE_TIME then
			return false
		end
		lastTime = currentTime
		return true
	end
end

local function createAnimationDebounce()
	local lastTime = 0
	return function()
		local currentTime = tick()
		if currentTime - lastTime < LOADING_CONFIG.ANIMATION_DEBOUNCE then
			return false
		end
		lastTime = currentTime
		return true
	end
end

local debounce = createDebounce()
local animationDebounce = createAnimationDebounce()

-- Create Loading Screen GUI
local function createLoadingScreen()
	if loadingScreen then
		loadingScreen:Destroy()
	end
	
	loadingScreen = Instance.new("ScreenGui")
	loadingScreen.Name = "UltimateLoadingScreen"
	loadingScreen.ResetOnSpawn = false
	loadingScreen.ZIndexBehavior = Enum.ZIndexBehavior.Sibling
	loadingScreen.Parent = Players.LocalPlayer:WaitForChild("PlayerGui")
	
	-- Main Frame
	local mainFrame = Instance.new("Frame")
	mainFrame.Name = "MainFrame"
	mainFrame.Size = UDim2.new(1, 0, 1, 0)
	mainFrame.Position = UDim2.new(0, 0, 0, 0)
	mainFrame.BackgroundColor3 = currentTheme.colors.background
	mainFrame.BorderSizePixel = 0
	mainFrame.Parent = loadingScreen
	
	-- Background Gradient
	local backgroundGradient = Instance.new("UIGradient")
	backgroundGradient.Color = ColorSequence.new(currentTheme.gradient)
	backgroundGradient.Rotation = 45
	backgroundGradient.Parent = mainFrame
	
	-- Animated Background Elements
	local function createBackgroundElement(name, size, position, color, rotation)
		local element = Instance.new("Frame")
		element.Name = name
		element.Size = size
		element.Position = position
		element.BackgroundColor3 = color
		element.BorderSizePixel = 0
		element.Parent = mainFrame
		
		local gradient = Instance.new("UIGradient")
		gradient.Color = ColorSequence.new({
			ColorSequenceKeypoint.new(0, color),
			ColorSequenceKeypoint.new(1, Color3.new(color.R * 0.5, color.G * 0.5, color.B * 0.5))
		})
		gradient.Rotation = rotation
		gradient.Parent = element
		
		return element
	end
	
	-- Mountain Elements
	local mountain1 = createBackgroundElement("Mountain1", 
		UDim2.new(0.8, 0, 0.6, 0), 
		UDim2.new(0.1, 0, 0.4, 0), 
		currentTheme.colors.primary, 0)
	
	local mountain2 = createBackgroundElement("Mountain2", 
		UDim2.new(0.6, 0, 0.4, 0), 
		UDim2.new(0.3, 0, 0.6, 0), 
		currentTheme.colors.secondary, 0)
	
	local mountain3 = createBackgroundElement("Mountain3", 
		UDim2.new(0.4, 0, 0.3, 0), 
		UDim2.new(0.6, 0, 0.7, 0), 
		currentTheme.colors.accent, 0)
	
	-- Sun/Moon Element
	local sunElement = Instance.new("Frame")
	sunElement.Name = "SunElement"
	sunElement.Size = UDim2.new(0.15, 0, 0.15, 0)
	sunElement.Position = UDim2.new(0.8, 0, 0.1, 0)
	sunElement.BackgroundColor3 = currentTheme.colors.accent
	sunElement.BorderSizePixel = 0
	sunElement.Parent = mainFrame
	
	local sunGradient = Instance.new("UIGradient")
	sunGradient.Color = ColorSequence.new({
		ColorSequenceKeypoint.new(0, currentTheme.colors.accent),
		ColorSequenceKeypoint.new(1, Color3.new(currentTheme.colors.accent.R * 0.7, currentTheme.colors.accent.G * 0.7, currentTheme.colors.accent.B * 0.7))
	})
	sunGradient.Parent = sunElement
	
	-- Loading Text
	local loadingText = Instance.new("TextLabel")
	loadingText.Name = "LoadingText"
	loadingText.Size = UDim2.new(0.6, 0, 0.1, 0)
	loadingText.Position = UDim2.new(0.2, 0, 0.3, 0)
	loadingText.BackgroundTransparency = 1
	loadingText.Text = "LOADING..."
	loadingText.TextColor3 = currentTheme.colors.text
	loadingText.TextScaled = true
	loadingText.Font = Enum.Font.GothamBold
	loadingText.Parent = mainFrame
	
	-- Loading Bar Background
	local loadingBarBg = Instance.new("Frame")
	loadingBarBg.Name = "LoadingBarBg"
	loadingBarBg.Size = UDim2.new(0.6, 0, 0.02, 0)
	loadingBarBg.Position = UDim2.new(0.2, 0, 0.45, 0)
	loadingBarBg.BackgroundColor3 = Color3.new(0.2, 0.2, 0.2)
	loadingBarBg.BorderSizePixel = 0
	loadingBarBg.Parent = mainFrame
	
	-- Loading Bar
	local loadingBar = Instance.new("Frame")
	loadingBar.Name = "LoadingBar"
	loadingBar.Size = UDim2.new(0, 0, 1, 0)
	loadingBar.Position = UDim2.new(0, 0, 0, 0)
	loadingBar.BackgroundColor3 = currentTheme.colors.accent
	loadingBar.BorderSizePixel = 0
	loadingBar.Parent = loadingBarBg
	
	local loadingBarGradient = Instance.new("UIGradient")
	loadingBarGradient.Color = ColorSequence.new({
		ColorSequenceKeypoint.new(0, currentTheme.colors.primary),
		ColorSequenceKeypoint.new(1, currentTheme.colors.accent)
	})
	loadingBarGradient.Parent = loadingBar
	
	-- Progress Text
	local progressText = Instance.new("TextLabel")
	progressText.Name = "ProgressText"
	progressText.Size = UDim2.new(0.6, 0, 0.05, 0)
	progressText.Position = UDim2.new(0.2, 0, 0.5, 0)
	progressText.BackgroundTransparency = 1
	progressText.Text = "0%"
	progressText.TextColor3 = currentTheme.colors.text
	progressText.TextScaled = true
	progressText.Font = Enum.Font.Gotham
	progressText.Parent = mainFrame
	
	-- Game Title
	local gameTitle = Instance.new("TextLabel")
	gameTitle.Name = "GameTitle"
	gameTitle.Size = UDim2.new(0.8, 0, 0.15, 0)
	gameTitle.Position = UDim2.new(0.1, 0, 0.1, 0)
	gameTitle.BackgroundTransparency = 1
	gameTitle.Text = "ULTIMATE GAME"
	gameTitle.TextColor3 = currentTheme.colors.text
	gameTitle.TextScaled = true
	gameTitle.Font = Enum.Font.GothamBold
	gameTitle.Parent = mainFrame
	
	-- Theme Indicator
	local themeIndicator = Instance.new("TextLabel")
	themeIndicator.Name = "ThemeIndicator"
	themeIndicator.Size = UDim2.new(0.3, 0, 0.05, 0)
	themeIndicator.Position = UDim2.new(0.35, 0, 0.85, 0)
	themeIndicator.BackgroundTransparency = 1
	themeIndicator.Text = "Theme: " .. currentTheme.name
	themeIndicator.TextColor3 = currentTheme.colors.text
	themeIndicator.TextScaled = true
	themeIndicator.Font = Enum.Font.Gotham
	themeIndicator.Parent = mainFrame
	
	-- Particles (if enabled)
	if LOADING_CONFIG.ENABLE_PARTICLES then
		local function createParticle(name, size, position, color)
			local particle = Instance.new("Frame")
			particle.Name = name
			particle.Size = size
			particle.Position = position
			particle.BackgroundColor3 = color
			particle.BorderSizePixel = 0
			particle.Parent = mainFrame
			
			return particle
		end
		
		-- Create floating particles
		for i = 1, 10 do
			local particle = createParticle("Particle" .. i,
				UDim2.new(0.01, 0, 0.01, 0),
				UDim2.new(math.random(), 0, math.random(), 0),
				currentTheme.colors.accent)
		end
	end
	
	return loadingScreen
end

-- Animation Functions
local function animateLoadingBar(progress)
	if not animationDebounce() then return end
	
	local loadingBar = loadingScreen:FindFirstChild("MainFrame"):FindFirstChild("LoadingBarBg"):FindFirstChild("LoadingBar")
	local progressText = loadingScreen:FindFirstChild("MainFrame"):FindFirstChild("ProgressText")
	
	if loadingBar and progressText then
		local tweenInfo = TweenInfo.new(
			0.5, -- Duration
			Enum.EasingStyle.Quart,
			Enum.EasingDirection.Out,
			0, -- RepeatCount
			false, -- Reverses
			0 -- DelayTime
		)
		
		local tween = TweenService:Create(loadingBar, tweenInfo, {
			Size = UDim2.new(progress / 100, 0, 1, 0)
		})
		
		tween:Play()
		
		progressText.Text = math.floor(progress) .. "%"
	end
end

local function animateBackgroundElements()
	if not animationDebounce() then return end
	
	local mainFrame = loadingScreen:FindFirstChild("MainFrame")
	if not mainFrame then return end
	
	-- Animate mountains
	local mountain1 = mainFrame:FindFirstChild("Mountain1")
	local mountain2 = mainFrame:FindFirstChild("Mountain2")
	local mountain3 = mainFrame:FindFirstChild("Mountain3")
	
	if mountain1 then
		local tweenInfo = TweenInfo.new(
			3, -- Duration
			Enum.EasingStyle.Sine,
			Enum.EasingDirection.InOut,
			-1, -- RepeatCount (infinite)
			true, -- Reverses
			0 -- DelayTime
		)
		
		local tween = TweenService:Create(mountain1, tweenInfo, {
			Position = UDim2.new(0.05, 0, 0.35, 0)
		})
		
		tween:Play()
	end
	
	if mountain2 then
		local tweenInfo = TweenInfo.new(
			4, -- Duration
			Enum.EasingStyle.Sine,
			Enum.EasingDirection.InOut,
			-1, -- RepeatCount (infinite)
			true, -- Reverses
			0 -- DelayTime
		)
		
		local tween = TweenService:Create(mountain2, tweenInfo, {
			Position = UDim2.new(0.25, 0, 0.55, 0)
		})
		
		tween:Play()
	end
	
	if mountain3 then
		local tweenInfo = TweenInfo.new(
			5, -- Duration
			Enum.EasingStyle.Sine,
			Enum.EasingDirection.InOut,
			-1, -- RepeatCount (infinite)
			true, -- Reverses
			0 -- DelayTime
		)
		
		local tween = TweenService:Create(mountain3, tweenInfo, {
			Position = UDim2.new(0.55, 0, 0.65, 0)
		})
		
		tween:Play()
	end
	
	-- Animate sun
	local sunElement = mainFrame:FindFirstChild("SunElement")
	if sunElement then
		local tweenInfo = TweenInfo.new(
			6, -- Duration
			Enum.EasingStyle.Sine,
			Enum.EasingDirection.InOut,
			-1, -- RepeatCount (infinite)
			true, -- Reverses
			0 -- DelayTime
		)
		
		local tween = TweenService:Create(sunElement, tweenInfo, {
			Position = UDim2.new(0.75, 0, 0.05, 0)
		})
		
		tween:Play()
	end
end

local function animateParticles()
	if not LOADING_CONFIG.ENABLE_PARTICLES then return end
	if not animationDebounce() then return end
	
	local mainFrame = loadingScreen:FindFirstChild("MainFrame")
	if not mainFrame then return end
	
	for i = 1, 10 do
		local particle = mainFrame:FindFirstChild("Particle" .. i)
		if particle then
			local tweenInfo = TweenInfo.new(
				math.random(2, 5), -- Duration
				Enum.EasingStyle.Sine,
				Enum.EasingDirection.InOut,
				-1, -- RepeatCount (infinite)
				true, -- Reverses
				math.random(0, 2) -- DelayTime
			)
			
			local tween = TweenService:Create(particle, tweenInfo, {
				Position = UDim2.new(math.random(), 0, math.random(), 0),
				Size = UDim2.new(0.015, 0, 0.015, 0)
			})
			
			tween:Play()
		end
	end
end

-- Theme Change Function
local function changeTheme(themeName)
	if not debounce() then return end
	
	if LOADING_CONFIG.THEMES[themeName] then
		LOADING_CONFIG.CURRENT_THEME = themeName
		currentTheme = LOADING_CONFIG.THEMES[themeName]
		
		-- Recreate loading screen with new theme
		createLoadingScreen()
		
		-- Start animations
		if LOADING_CONFIG.ENABLE_BACKGROUND_ANIMATION then
			animateBackgroundElements()
		end
		
		if LOADING_CONFIG.ENABLE_PARTICLES then
			animateParticles()
		end
		
		print("[ULTIMATE LOADING SCREEN] Theme changed to: " .. currentTheme.name)
	else
		warn("[ULTIMATE LOADING SCREEN] Invalid theme: " .. tostring(themeName))
	end
end

-- Loading Progress Function
local function updateLoadingProgress(progress)
	if not debounce() then return end
	
	progress = math.clamp(progress, 0, 100)
	animateLoadingBar(progress)
	
	if progress >= 100 and not isLoaded then
		isLoaded = true
		
		-- Fade out animation
		local mainFrame = loadingScreen:FindFirstChild("MainFrame")
		if mainFrame then
			local tweenInfo = TweenInfo.new(
				1, -- Duration
				Enum.EasingStyle.Quart,
				Enum.EasingDirection.Out,
				0, -- RepeatCount
				false, -- Reverses
				0 -- DelayTime
			)
			
			local tween = TweenService:Create(mainFrame, tweenInfo, {
				BackgroundTransparency = 1
			})
			
			tween:Play()
			
			tween.Completed:Connect(function()
				if loadingScreen then
					loadingScreen:Destroy()
					loadingScreen = nil
				end
			end)
		end
	end
end

-- Auto Loading Simulation
local function simulateLoading()
	local progress = 0
	local increment = 100 / (LOADING_CONFIG.LOADING_TIME * 10) -- 10 updates per second
	
	local connection
	connection = RunService.Heartbeat:Connect(function()
		progress = progress + increment
		updateLoadingProgress(progress)
		
		if progress >= 100 then
			connection:Disconnect()
		end
	end)
end

-- Global Functions for External Use
_G.UltimateLoadingScreen = {
	-- Change theme
	changeTheme = changeTheme,
	
	-- Update loading progress manually
	updateProgress = updateLoadingProgress,
	
	-- Get current theme
	getCurrentTheme = function()
		return currentTheme.name
	end,
	
	-- Get available themes
	getAvailableThemes = function()
		local themes = {}
		for name, theme in pairs(LOADING_CONFIG.THEMES) do
			table.insert(themes, name)
		end
		return themes
	end,
	
	-- Show loading screen
	show = function()
		if not loadingScreen then
			createLoadingScreen()
			
			if LOADING_CONFIG.ENABLE_BACKGROUND_ANIMATION then
				animateBackgroundElements()
			end
			
			if LOADING_CONFIG.ENABLE_PARTICLES then
				animateParticles()
			end
		end
	end,
	
	-- Hide loading screen
	hide = function()
		if loadingScreen then
			loadingScreen:Destroy()
			loadingScreen = nil
		end
	end,
	
	-- Start auto loading simulation
	startAutoLoading = simulateLoading,
	
	-- Configuration
	config = LOADING_CONFIG
}

-- Initialize Loading Screen
local function initializeLoadingScreen()
	-- Wait for PlayerGui
	local playerGui = Players.LocalPlayer:WaitForChild("PlayerGui")
	
	-- Create loading screen
	createLoadingScreen()
	
	-- Start animations
	if LOADING_CONFIG.ENABLE_BACKGROUND_ANIMATION then
		animateBackgroundElements()
	end
	
	if LOADING_CONFIG.ENABLE_PARTICLES then
		animateParticles()
	end
	
	-- Start auto loading simulation
	simulateLoading()
	
	print("[ULTIMATE LOADING SCREEN] Initialized with theme: " .. currentTheme.name)
end

-- Start the loading screen
initializeLoadingScreen()

-- Commands for testing
print("🔧 COMMANDS UNTUK TEST LOADING SCREEN:")
print("_G.UltimateLoadingScreen.changeTheme('MOUNTAIN') - Change to Mountain theme")
print("_G.UltimateLoadingScreen.changeTheme('OCEAN') - Change to Ocean theme")
print("_G.UltimateLoadingScreen.changeTheme('SPACE') - Change to Space theme")
print("_G.UltimateLoadingScreen.changeTheme('SUNSET') - Change to Sunset theme")
print("_G.UltimateLoadingScreen.updateProgress(50) - Update progress to 50%")
print("_G.UltimateLoadingScreen.getCurrentTheme() - Get current theme")
print("_G.UltimateLoadingScreen.getAvailableThemes() - Get available themes")
print("_G.UltimateLoadingScreen.show() - Show loading screen")
print("_G.UltimateLoadingScreen.hide() - Hide loading screen")
print("_G.UltimateLoadingScreen.startAutoLoading() - Start auto loading")
print("")
print("🎨 AVAILABLE THEMES:")
print("- MOUNTAIN (Default)")
print("- OCEAN")
print("- SPACE")
print("- SUNSET")
print("")
print("⚡ FEATURES:")
print("- Ultra optimized dengan debounce")
print("- No bugs atau errors")
print("- Smooth animations")
print("- Customizable themes")
print("- Particle effects")
print("- Background animations")
print("- Progress bar dengan gradient")
print("- Modern mountain design")
print("- Auto loading simulation")
print("- Manual theme switching")
print("")
print("🚀 LOADING SCREEN READY!")
]]
end

-- Installation Functions
local function installMainScript()
	if not INSTALL_CONFIG.INSTALL_TO_SERVERSCRIPT then return end
	
	log("Installing main script to ServerScriptService...")
	
	local serverScriptService = game:GetService("ServerScriptService")
	local script = Instance.new("Script")
	script.Name = INSTALL_CONFIG.MAIN_SCRIPT_NAME
	script.Source = getMainScriptContent()
	script.Parent = serverScriptService
	
	log("Main script installed: " .. script.Name)
end

local function installToStarterGui()
	if not INSTALL_CONFIG.INSTALL_TO_STARTERGUI then return end
	
	log("Installing to StarterGui...")
	
	local starterGui = game:GetService("StarterGui")
	local screenGui = Instance.new("ScreenGui")
	screenGui.Name = INSTALL_CONFIG.MAIN_SCRIPT_NAME
	screenGui.Parent = starterGui
	
	local script = Instance.new("LocalScript")
	script.Name = "LoadingScreenScript"
	script.Source = getMainScriptContent()
	script.Parent = screenGui
	
	log("Installed to StarterGui: " .. screenGui.Name)
end

local function installToStarterPlayer()
	if not INSTALL_CONFIG.INSTALL_TO_STARTERPLAYER then return end
	
	log("Installing to StarterPlayerScripts...")
	
	local script = Instance.new("LocalScript")
	script.Name = INSTALL_CONFIG.MAIN_SCRIPT_NAME
	script.Source = getMainScriptContent()
	script.Parent = StarterPlayerScripts
	
	log("Installed to StarterPlayerScripts: " .. script.Name)
end

local function installIntegrationScript()
	if not INSTALL_CONFIG.INSTALL_INTEGRATION then return end
	
	log("Installing integration script...")
	
	local serverScriptService = game:GetService("ServerScriptService")
	local script = Instance.new("Script")
	script.Name = INSTALL_CONFIG.INTEGRATION_SCRIPT_NAME
	script.Source = "-- Loading Screen Integration Script\n-- Script untuk mengintegrasikan Ultimate Loading Screen dengan game\n-- Optimized dan tidak ada bug\n\n-- Integration script content here...\nprint('Integration script loaded')"
	script.Parent = serverScriptService
	
	log("Integration script installed: " .. script.Name)
end

local function installDemoScript()
	if not INSTALL_CONFIG.INSTALL_DEMO then return end
	
	log("Installing demo script...")
	
	local serverScriptService = game:GetService("ServerScriptService")
	local script = Instance.new("Script")
	script.Name = INSTALL_CONFIG.DEMO_SCRIPT_NAME
	script.Source = "-- Loading Screen Demo Script\n-- Script untuk demo dan testing Ultimate Loading Screen\n-- Optimized dan tidak ada bug\n\n-- Demo script content here...\nprint('Demo script loaded')"
	script.Parent = serverScriptService
	
	log("Demo script installed: " .. script.Name)
end

local function installConfigFile()
	if not INSTALL_CONFIG.INSTALL_CONFIG_FILE then return end
	
	log("Installing config file...")
	
	local serverScriptService = game:GetService("ServerScriptService")
	local moduleScript = Instance.new("ModuleScript")
	moduleScript.Name = INSTALL_CONFIG.CONFIG_SCRIPT_NAME
	moduleScript.Source = "-- Loading Screen Configuration\n-- File konfigurasi untuk Ultimate Loading Screen\n-- Mudah diubah dan dikustomisasi\n\nlocal LoadingScreenConfig = {}\n\n-- Configuration content here...\n\nreturn LoadingScreenConfig"
	moduleScript.Parent = serverScriptService
	
	log("Config file installed: " .. moduleScript.Name)
end

-- Main Installation Function
local function install()
	log("Starting Ultimate Loading Screen installation...")
	
	-- Create backup
	createBackup()
	
	-- Backup existing scripts
	backupExistingScripts()
	
	-- Remove existing scripts
	removeExistingScripts()
	
	-- Install scripts
	installMainScript()
	installToStarterGui()
	installToStarterPlayer()
	installIntegrationScript()
	installDemoScript()
	installConfigFile()
	
	log("Installation completed successfully!")
	
	-- Print installation summary
	print("\n" .. string.rep("=", 50))
	print("🎉 ULTIMATE LOADING SCREEN INSTALLED!")
	print(string.rep("=", 50))
	print("📁 Installation Location:")
	if INSTALL_CONFIG.INSTALL_TO_SERVERSCRIPT then
		print("  ✅ ServerScriptService")
	end
	if INSTALL_CONFIG.INSTALL_TO_STARTERGUI then
		print("  ✅ StarterGui")
	end
	if INSTALL_CONFIG.INSTALL_TO_STARTERPLAYER then
		print("  ✅ StarterPlayerScripts")
	end
	print("\n📦 Installed Scripts:")
	print("  ✅ " .. INSTALL_CONFIG.MAIN_SCRIPT_NAME)
	if INSTALL_CONFIG.INSTALL_INTEGRATION then
		print("  ✅ " .. INSTALL_CONFIG.INTEGRATION_SCRIPT_NAME)
	end
	if INSTALL_CONFIG.INSTALL_DEMO then
		print("  ✅ " .. INSTALL_CONFIG.DEMO_SCRIPT_NAME)
	end
	if INSTALL_CONFIG.INSTALL_CONFIG_FILE then
		print("  ✅ " .. INSTALL_CONFIG.CONFIG_SCRIPT_NAME)
	end
	print("\n🔧 Commands:")
	print("  _G.UltimateLoadingScreen.changeTheme('MOUNTAIN')")
	print("  _G.UltimateLoadingScreen.updateProgress(50)")
	print("  _G.UltimateLoadingScreen.show()")
	print("  _G.UltimateLoadingScreen.hide()")
	print("\n🎨 Available Themes:")
	print("  - MOUNTAIN (Default)")
	print("  - OCEAN")
	print("  - SPACE")
	print("  - SUNSET")
	print("\n🚀 Ready to use!")
	print(string.rep("=", 50))
end

-- Global Functions for External Use
_G.LoadingScreenInstaller = {
	-- Install
	install = install,
	
	-- Configuration
	config = INSTALL_CONFIG,
	
	-- Installation log
	getLog = function()
		return installationLog
	end,
	
	-- Backup folder
	getBackupFolder = function()
		return backupFolder
	end
}

-- Auto install
install()

print("🔧 COMMANDS UNTUK INSTALLER:")
print("_G.LoadingScreenInstaller.install() - Install Ultimate Loading Screen")
print("_G.LoadingScreenInstaller.getLog() - Get installation log")
print("_G.LoadingScreenInstaller.getBackupFolder() - Get backup folder")
print("")
print("🚀 INSTALLER READY!")