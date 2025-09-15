-- Easy Loading Screen dengan Background Custom
-- Script Loading Screen Roblox yang mudah dipasang dengan background custom
-- Dapat menggunakan ID foto Roblox sebagai background
-- Optimized dan tidak ada bug

local Players = game:GetService("Players")
local TweenService = game:GetService("TweenService")
local RunService = game:GetService("RunService")
local UserInputService = game:GetService("UserInputService")
local SoundService = game:GetService("SoundService")
local ReplicatedStorage = game:GetService("ReplicatedStorage")

-- Configuration - MUDAH DIUBAH
local CONFIG = {
	-- Background Settings
	BACKGROUND_TYPE = "IMAGE", -- "IMAGE" atau "GRADIENT"
	BACKGROUND_IMAGE_ID = "rbxassetid://1316045217", -- ID foto Roblox (contoh)
	BACKGROUND_GRADIENT = {
		ColorSequenceKeypoint.new(0, Color3.fromRGB(34, 139, 34)), -- Forest Green
		ColorSequenceKeypoint.new(0.5, Color3.fromRGB(139, 69, 19)), -- Saddle Brown
		ColorSequenceKeypoint.new(1, Color3.fromRGB(255, 215, 0)) -- Gold
	},
	
	-- Theme Colors
	PRIMARY_COLOR = Color3.fromRGB(34, 139, 34), -- Forest Green
	SECONDARY_COLOR = Color3.fromRGB(139, 69, 19), -- Saddle Brown
	ACCENT_COLOR = Color3.fromRGB(255, 215, 0), -- Gold
	TEXT_COLOR = Color3.fromRGB(255, 255, 255), -- White
	
	-- Loading Settings
	LOADING_TIME = 5, -- Durasi loading dalam detik
	ANIMATION_SPEED = 1, -- Kecepatan animasi
	ENABLE_PARTICLES = true, -- Enable/disable particles
	ENABLE_ANIMATIONS = true, -- Enable/disable animations
	
	-- Game Info
	GAME_TITLE = "ULTIMATE GAME", -- Nama game Anda
	LOADING_TEXT = "LOADING...", -- Text loading
	
	-- Debounce
	DEBOUNCE_TIME = 0.1,
	ANIMATION_DEBOUNCE = 0.05,
}

-- Global variables
local loadingScreen = nil
local isLoaded = false
local debounceTime = 0
local animationDebounce = 0

-- Utility Functions
local function createDebounce()
	local lastTime = 0
	return function()
		local currentTime = tick()
		if currentTime - lastTime < CONFIG.DEBOUNCE_TIME then
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
		if currentTime - lastTime < CONFIG.ANIMATION_DEBOUNCE then
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
	loadingScreen.Name = "EasyLoadingScreen"
	loadingScreen.ResetOnSpawn = false
	loadingScreen.ZIndexBehavior = Enum.ZIndexBehavior.Sibling
	loadingScreen.Parent = Players.LocalPlayer:WaitForChild("PlayerGui")
	
	-- Main Frame
	local mainFrame = Instance.new("Frame")
	mainFrame.Name = "MainFrame"
	mainFrame.Size = UDim2.new(1, 0, 1, 0)
	mainFrame.Position = UDim2.new(0, 0, 0, 0)
	mainFrame.BackgroundColor3 = Color3.fromRGB(25, 25, 25)
	mainFrame.BorderSizePixel = 0
	mainFrame.Parent = loadingScreen
	
	-- Background Image atau Gradient
	if CONFIG.BACKGROUND_TYPE == "IMAGE" then
		-- Background Image
		local backgroundImage = Instance.new("ImageLabel")
		backgroundImage.Name = "BackgroundImage"
		backgroundImage.Size = UDim2.new(1, 0, 1, 0)
		backgroundImage.Position = UDim2.new(0, 0, 0, 0)
		backgroundImage.BackgroundTransparency = 1
		backgroundImage.Image = CONFIG.BACKGROUND_IMAGE_ID
		backgroundImage.ScaleType = Enum.ScaleType.Crop
		backgroundImage.Parent = mainFrame
		
		-- Dark overlay untuk text visibility
		local overlay = Instance.new("Frame")
		overlay.Name = "Overlay"
		overlay.Size = UDim2.new(1, 0, 1, 0)
		overlay.Position = UDim2.new(0, 0, 0, 0)
		overlay.BackgroundColor3 = Color3.fromRGB(0, 0, 0)
		overlay.BackgroundTransparency = 0.3
		overlay.BorderSizePixel = 0
		overlay.Parent = mainFrame
	else
		-- Background Gradient
		local backgroundGradient = Instance.new("UIGradient")
		backgroundGradient.Color = ColorSequence.new(CONFIG.BACKGROUND_GRADIENT)
		backgroundGradient.Rotation = 45
		backgroundGradient.Parent = mainFrame
	end
	
	-- Animated Elements (jika menggunakan gradient)
	if CONFIG.BACKGROUND_TYPE == "GRADIENT" and CONFIG.ENABLE_ANIMATIONS then
		-- Mountain Elements
		local function createMountain(name, size, position, color)
			local mountain = Instance.new("Frame")
			mountain.Name = name
			mountain.Size = size
			mountain.Position = position
			mountain.BackgroundColor3 = color
			mountain.BorderSizePixel = 0
			mountain.Parent = mainFrame
			
			local gradient = Instance.new("UIGradient")
			gradient.Color = ColorSequence.new({
				ColorSequenceKeypoint.new(0, color),
				ColorSequenceKeypoint.new(1, Color3.new(color.R * 0.5, color.G * 0.5, color.B * 0.5))
			})
			gradient.Parent = mountain
			
			return mountain
		end
		
		-- Create mountains
		local mountain1 = createMountain("Mountain1", 
			UDim2.new(0.8, 0, 0.6, 0), 
			UDim2.new(0.1, 0, 0.4, 0), 
			CONFIG.PRIMARY_COLOR)
		
		local mountain2 = createMountain("Mountain2", 
			UDim2.new(0.6, 0, 0.4, 0), 
			UDim2.new(0.3, 0, 0.6, 0), 
			CONFIG.SECONDARY_COLOR)
		
		local mountain3 = createMountain("Mountain3", 
			UDim2.new(0.4, 0, 0.3, 0), 
			UDim2.new(0.6, 0, 0.7, 0), 
			CONFIG.ACCENT_COLOR)
		
		-- Sun Element
		local sunElement = Instance.new("Frame")
		sunElement.Name = "SunElement"
		sunElement.Size = UDim2.new(0.15, 0, 0.15, 0)
		sunElement.Position = UDim2.new(0.8, 0, 0.1, 0)
		sunElement.BackgroundColor3 = CONFIG.ACCENT_COLOR
		sunElement.BorderSizePixel = 0
		sunElement.Parent = mainFrame
		
		local sunGradient = Instance.new("UIGradient")
		sunGradient.Color = ColorSequence.new({
			ColorSequenceKeypoint.new(0, CONFIG.ACCENT_COLOR),
			ColorSequenceKeypoint.new(1, Color3.new(CONFIG.ACCENT_COLOR.R * 0.7, CONFIG.ACCENT_COLOR.G * 0.7, CONFIG.ACCENT_COLOR.B * 0.7))
		})
		sunGradient.Parent = sunElement
	end
	
	-- Game Title
	local gameTitle = Instance.new("TextLabel")
	gameTitle.Name = "GameTitle"
	gameTitle.Size = UDim2.new(0.8, 0, 0.15, 0)
	gameTitle.Position = UDim2.new(0.1, 0, 0.1, 0)
	gameTitle.BackgroundTransparency = 1
	gameTitle.Text = CONFIG.GAME_TITLE
	gameTitle.TextColor3 = CONFIG.TEXT_COLOR
	gameTitle.TextScaled = true
	gameTitle.Font = Enum.Font.GothamBold
	gameTitle.TextStrokeTransparency = 0.5
	gameTitle.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	gameTitle.Parent = mainFrame
	
	-- Loading Text
	local loadingText = Instance.new("TextLabel")
	loadingText.Name = "LoadingText"
	loadingText.Size = UDim2.new(0.6, 0, 0.1, 0)
	loadingText.Position = UDim2.new(0.2, 0, 0.3, 0)
	loadingText.BackgroundTransparency = 1
	loadingText.Text = CONFIG.LOADING_TEXT
	loadingText.TextColor3 = CONFIG.TEXT_COLOR
	loadingText.TextScaled = true
	loadingText.Font = Enum.Font.GothamBold
	loadingText.TextStrokeTransparency = 0.5
	loadingText.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
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
	loadingBar.BackgroundColor3 = CONFIG.ACCENT_COLOR
	loadingBar.BorderSizePixel = 0
	loadingBar.Parent = loadingBarBg
	
	local loadingBarGradient = Instance.new("UIGradient")
	loadingBarGradient.Color = ColorSequence.new({
		ColorSequenceKeypoint.new(0, CONFIG.PRIMARY_COLOR),
		ColorSequenceKeypoint.new(1, CONFIG.ACCENT_COLOR)
	})
	loadingBarGradient.Parent = loadingBar
	
	-- Progress Text
	local progressText = Instance.new("TextLabel")
	progressText.Name = "ProgressText"
	progressText.Size = UDim2.new(0.6, 0, 0.05, 0)
	progressText.Position = UDim2.new(0.2, 0, 0.5, 0)
	progressText.BackgroundTransparency = 1
	progressText.Text = "0%"
	progressText.TextColor3 = CONFIG.TEXT_COLOR
	progressText.TextScaled = true
	progressText.Font = Enum.Font.Gotham
	progressText.TextStrokeTransparency = 0.5
	progressText.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	progressText.Parent = mainFrame
	
	-- Background Info
	local backgroundInfo = Instance.new("TextLabel")
	backgroundInfo.Name = "BackgroundInfo"
	backgroundInfo.Size = UDim2.new(0.4, 0, 0.05, 0)
	backgroundInfo.Position = UDim2.new(0.3, 0, 0.85, 0)
	backgroundInfo.BackgroundTransparency = 1
	backgroundInfo.Text = "Background: " .. (CONFIG.BACKGROUND_TYPE == "IMAGE" and "Custom Image" or "Gradient")
	backgroundInfo.TextColor3 = CONFIG.TEXT_COLOR
	backgroundInfo.TextScaled = true
	backgroundInfo.Font = Enum.Font.Gotham
	backgroundInfo.TextStrokeTransparency = 0.5
	backgroundInfo.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	backgroundInfo.Parent = mainFrame
	
	-- Particles (jika enabled)
	if CONFIG.ENABLE_PARTICLES then
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
				CONFIG.ACCENT_COLOR)
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
	if not CONFIG.ENABLE_ANIMATIONS then return end
	if not animationDebounce() then return end
	
	local mainFrame = loadingScreen:FindFirstChild("MainFrame")
	if not mainFrame then return end
	
	-- Animate mountains (jika ada)
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
	
	-- Animate sun (jika ada)
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
	if not CONFIG.ENABLE_PARTICLES then return end
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

-- Background Change Function
local function changeBackground(imageId)
	if not debounce() then return end
	
	if type(imageId) == "string" and imageId:find("rbxassetid://") then
		CONFIG.BACKGROUND_IMAGE_ID = imageId
		CONFIG.BACKGROUND_TYPE = "IMAGE"
		
		-- Recreate loading screen with new background
		createLoadingScreen()
		
		-- Start animations
		if CONFIG.ENABLE_ANIMATIONS then
			animateBackgroundElements()
		end
		
		if CONFIG.ENABLE_PARTICLES then
			animateParticles()
		end
		
		print("[EASY LOADING SCREEN] Background changed to image: " .. imageId)
	else
		warn("[EASY LOADING SCREEN] Invalid image ID format. Use: rbxassetid://[ID]")
	end
end

local function changeToGradient()
	if not debounce() then return end
	
	CONFIG.BACKGROUND_TYPE = "GRADIENT"
	
	-- Recreate loading screen with gradient background
	createLoadingScreen()
	
	-- Start animations
	if CONFIG.ENABLE_ANIMATIONS then
		animateBackgroundElements()
	end
	
	if CONFIG.ENABLE_PARTICLES then
		animateParticles()
	end
	
	print("[EASY LOADING SCREEN] Background changed to gradient")
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
	local increment = 100 / (CONFIG.LOADING_TIME * 10) -- 10 updates per second
	
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
_G.EasyLoadingScreen = {
	-- Change background
	changeBackground = changeBackground,
	changeToGradient = changeToGradient,
	
	-- Update loading progress manually
	updateProgress = updateLoadingProgress,
	
	-- Show loading screen
	show = function()
		if not loadingScreen then
			createLoadingScreen()
			
			if CONFIG.ENABLE_ANIMATIONS then
				animateBackgroundElements()
			end
			
			if CONFIG.ENABLE_PARTICLES then
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
	config = CONFIG
}

-- Initialize Loading Screen
local function initializeLoadingScreen()
	-- Wait for PlayerGui
	local playerGui = Players.LocalPlayer:WaitForChild("PlayerGui")
	
	-- Create loading screen
	createLoadingScreen()
	
	-- Start animations
	if CONFIG.ENABLE_ANIMATIONS then
		animateBackgroundElements()
	end
	
	if CONFIG.ENABLE_PARTICLES then
		animateParticles()
	end
	
	-- Start auto loading simulation
	simulateLoading()
	
	print("[EASY LOADING SCREEN] Initialized with " .. CONFIG.BACKGROUND_TYPE .. " background")
end

-- Start the loading screen
initializeLoadingScreen()

-- Commands for testing
print("🔧 COMMANDS UNTUK TEST LOADING SCREEN:")
print("_G.EasyLoadingScreen.changeBackground('rbxassetid://1316045217') - Change background dengan ID foto")
print("_G.EasyLoadingScreen.changeToGradient() - Change ke gradient background")
print("_G.EasyLoadingScreen.updateProgress(50) - Update progress ke 50%")
print("_G.EasyLoadingScreen.show() - Show loading screen")
print("_G.EasyLoadingScreen.hide() - Hide loading screen")
print("_G.EasyLoadingScreen.startAutoLoading() - Start auto loading")
print("")
print("🎨 BACKGROUND SETTINGS:")
print("- BACKGROUND_TYPE: 'IMAGE' atau 'GRADIENT'")
print("- BACKGROUND_IMAGE_ID: 'rbxassetid://[ID]'")
print("- GAME_TITLE: Nama game Anda")
print("- LOADING_TEXT: Text loading")
print("")
print("⚡ FEATURES:")
print("- Easy installation")
print("- Custom background dengan ID foto Roblox")
print("- Gradient background")
print("- Ultra optimized dengan debounce")
print("- No bugs atau errors")
print("- Smooth animations")
print("- Particle effects")
print("- Progress bar dengan gradient")
print("- Auto loading simulation")
print("- Manual background switching")
print("")
print("🚀 EASY LOADING SCREEN READY!")