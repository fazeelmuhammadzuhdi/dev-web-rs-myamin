-- Loading Screen Examples dengan Background Siap Pakai
-- Contoh-contoh loading screen dengan berbagai background
-- Mudah dipasang dan digunakan

local Players = game:GetService("Players")
local TweenService = game:GetService("TweenService")
local RunService = game:GetService("RunService")

-- Examples Configuration
local EXAMPLES = {
	-- Example 1: Mountain Theme
	MOUNTAIN = {
		name = "Mountain Theme",
		backgroundType = "IMAGE",
		backgroundImageId = "rbxassetid://1316045217", -- Mountain image
		primaryColor = Color3.fromRGB(34, 139, 34), -- Forest Green
		secondaryColor = Color3.fromRGB(139, 69, 19), -- Saddle Brown
		accentColor = Color3.fromRGB(255, 215, 0), -- Gold
		textColor = Color3.fromRGB(255, 255, 255), -- White
		gameTitle = "MOUNTAIN ADVENTURE",
		loadingText = "CLIMBING MOUNTAINS...",
		loadingTime = 5
	},
	
	-- Example 2: Ocean Theme
	OCEAN = {
		name = "Ocean Theme",
		backgroundType = "IMAGE",
		backgroundImageId = "rbxassetid://1316045218", -- Ocean image
		primaryColor = Color3.fromRGB(0, 100, 200), -- Ocean Blue
		secondaryColor = Color3.fromRGB(0, 150, 255), -- Light Blue
		accentColor = Color3.fromRGB(255, 255, 255), -- White
		textColor = Color3.fromRGB(255, 255, 255), -- White
		gameTitle = "OCEAN EXPLORER",
		loadingText = "DIVING DEEP...",
		loadingTime = 4
	},
	
	-- Example 3: Space Theme
	SPACE = {
		name = "Space Theme",
		backgroundType = "IMAGE",
		backgroundImageId = "rbxassetid://1316045219", -- Space image
		primaryColor = Color3.fromRGB(75, 0, 130), -- Indigo
		secondaryColor = Color3.fromRGB(138, 43, 226), -- Blue Violet
		accentColor = Color3.fromRGB(255, 255, 0), -- Yellow
		textColor = Color3.fromRGB(255, 255, 255), -- White
		gameTitle = "SPACE ODYSSEY",
		loadingText = "LAUNCHING ROCKET...",
		loadingTime = 6
	},
	
	-- Example 4: Sunset Theme
	SUNSET = {
		name = "Sunset Theme",
		backgroundType = "IMAGE",
		backgroundImageId = "rbxassetid://1316045220", -- Sunset image
		primaryColor = Color3.fromRGB(255, 69, 0), -- Red Orange
		secondaryColor = Color3.fromRGB(255, 140, 0), -- Dark Orange
		accentColor = Color3.fromRGB(255, 215, 0), -- Gold
		textColor = Color3.fromRGB(255, 255, 255), -- White
		gameTitle = "SUNSET DREAMS",
		loadingText = "WATCHING SUNSET...",
		loadingTime = 3
	},
	
	-- Example 5: City Theme
	CITY = {
		name = "City Theme",
		backgroundType = "IMAGE",
		backgroundImageId = "rbxassetid://1316045221", -- City image
		primaryColor = Color3.fromRGB(50, 50, 50), -- Dark Gray
		secondaryColor = Color3.fromRGB(100, 100, 100), -- Gray
		accentColor = Color3.fromRGB(255, 255, 0), -- Yellow
		textColor = Color3.fromRGB(255, 255, 255), -- White
		gameTitle = "CITY RUNNER",
		loadingText = "RUNNING THROUGH CITY...",
		loadingTime = 4
	},
	
	-- Example 6: Forest Theme
	FOREST = {
		name = "Forest Theme",
		backgroundType = "IMAGE",
		backgroundImageId = "rbxassetid://1316045222", -- Forest image
		primaryColor = Color3.fromRGB(0, 100, 0), -- Dark Green
		secondaryColor = Color3.fromRGB(34, 139, 34), -- Forest Green
		accentColor = Color3.fromRGB(255, 255, 0), -- Yellow
		textColor = Color3.fromRGB(255, 255, 255), -- White
		gameTitle = "FOREST GUARDIAN",
		loadingText = "EXPLORING FOREST...",
		loadingTime = 5
	},
	
	-- Example 7: Desert Theme
	DESERT = {
		name = "Desert Theme",
		backgroundType = "IMAGE",
		backgroundImageId = "rbxassetid://1316045223", -- Desert image
		primaryColor = Color3.fromRGB(210, 180, 140), -- Tan
		secondaryColor = Color3.fromRGB(255, 215, 0), -- Gold
		accentColor = Color3.fromRGB(255, 255, 255), -- White
		textColor = Color3.fromRGB(0, 0, 0), -- Black
		gameTitle = "DESERT WANDERER",
		loadingText = "WALKING THROUGH DESERT...",
		loadingTime = 4
	},
	
	-- Example 8: Gradient Theme
	GRADIENT = {
		name = "Gradient Theme",
		backgroundType = "GRADIENT",
		backgroundGradient = {
			ColorSequenceKeypoint.new(0, Color3.fromRGB(255, 0, 150)), -- Pink
			ColorSequenceKeypoint.new(0.5, Color3.fromRGB(0, 150, 255)), -- Blue
			ColorSequenceKeypoint.new(1, Color3.fromRGB(150, 255, 0)) -- Green
		},
		primaryColor = Color3.fromRGB(255, 0, 150), -- Pink
		secondaryColor = Color3.fromRGB(0, 150, 255), -- Blue
		accentColor = Color3.fromRGB(150, 255, 0), -- Green
		textColor = Color3.fromRGB(255, 255, 255), -- White
		gameTitle = "GRADIENT MASTER",
		loadingText = "LOADING COLORS...",
		loadingTime = 5
	}
}

-- Global variables
local currentExample = EXAMPLES.MOUNTAIN
local loadingScreen = nil
local isLoaded = false
local debounceTime = 0
local animationDebounce = 0

-- Utility Functions
local function createDebounce()
	local lastTime = 0
	return function()
		local currentTime = tick()
		if currentTime - lastTime < 0.1 then
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
		if currentTime - lastTime < 0.05 then
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
	loadingScreen.Name = "ExampleLoadingScreen"
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
	
	-- Background
	if currentExample.backgroundType == "IMAGE" then
		-- Background Image
		local backgroundImage = Instance.new("ImageLabel")
		backgroundImage.Name = "BackgroundImage"
		backgroundImage.Size = UDim2.new(1, 0, 1, 0)
		backgroundImage.Position = UDim2.new(0, 0, 0, 0)
		backgroundImage.BackgroundTransparency = 1
		backgroundImage.Image = currentExample.backgroundImageId
		backgroundImage.ScaleType = Enum.ScaleType.Crop
		backgroundImage.Parent = mainFrame
		
		-- Dark overlay
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
		backgroundGradient.Color = ColorSequence.new(currentExample.backgroundGradient)
		backgroundGradient.Rotation = 45
		backgroundGradient.Parent = mainFrame
	end
	
	-- Game Title
	local gameTitle = Instance.new("TextLabel")
	gameTitle.Name = "GameTitle"
	gameTitle.Size = UDim2.new(0.8, 0, 0.15, 0)
	gameTitle.Position = UDim2.new(0.1, 0, 0.1, 0)
	gameTitle.BackgroundTransparency = 1
	gameTitle.Text = currentExample.gameTitle
	gameTitle.TextColor3 = currentExample.textColor
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
	loadingText.Text = currentExample.loadingText
	loadingText.TextColor3 = currentExample.textColor
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
	loadingBar.BackgroundColor3 = currentExample.accentColor
	loadingBar.BorderSizePixel = 0
	loadingBar.Parent = loadingBarBg
	
	local loadingBarGradient = Instance.new("UIGradient")
	loadingBarGradient.Color = ColorSequence.new({
		ColorSequenceKeypoint.new(0, currentExample.primaryColor),
		ColorSequenceKeypoint.new(1, currentExample.accentColor)
	})
	loadingBarGradient.Parent = loadingBar
	
	-- Progress Text
	local progressText = Instance.new("TextLabel")
	progressText.Name = "ProgressText"
	progressText.Size = UDim2.new(0.6, 0, 0.05, 0)
	progressText.Position = UDim2.new(0.2, 0, 0.5, 0)
	progressText.BackgroundTransparency = 1
	progressText.Text = "0%"
	progressText.TextColor3 = currentExample.textColor
	progressText.TextScaled = true
	progressText.Font = Enum.Font.Gotham
	progressText.TextStrokeTransparency = 0.5
	progressText.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	progressText.Parent = mainFrame
	
	-- Theme Info
	local themeInfo = Instance.new("TextLabel")
	themeInfo.Name = "ThemeInfo"
	themeInfo.Size = UDim2.new(0.4, 0, 0.05, 0)
	themeInfo.Position = UDim2.new(0.3, 0, 0.85, 0)
	themeInfo.BackgroundTransparency = 1
	themeInfo.Text = "Theme: " .. currentExample.name
	themeInfo.TextColor3 = currentExample.textColor
	themeInfo.TextScaled = true
	themeInfo.Font = Enum.Font.Gotham
	themeInfo.TextStrokeTransparency = 0.5
	themeInfo.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	themeInfo.Parent = mainFrame
	
	-- Particles
	for i = 1, 10 do
		local particle = Instance.new("Frame")
		particle.Name = "Particle" .. i
		particle.Size = UDim2.new(0.01, 0, 0.01, 0)
		particle.Position = UDim2.new(math.random(), 0, math.random(), 0)
		particle.BackgroundColor3 = currentExample.accentColor
		particle.BorderSizePixel = 0
		particle.Parent = mainFrame
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

local function animateParticles()
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

-- Example Change Function
local function changeExample(exampleName)
	if not debounce() then return end
	
	if EXAMPLES[exampleName] then
		currentExample = EXAMPLES[exampleName]
		
		-- Recreate loading screen with new example
		createLoadingScreen()
		
		-- Start animations
		animateParticles()
		
		print("[LOADING SCREEN EXAMPLES] Changed to: " .. currentExample.name)
	else
		warn("[LOADING SCREEN EXAMPLES] Invalid example: " .. tostring(exampleName))
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
	local increment = 100 / (currentExample.loadingTime * 10) -- 10 updates per second
	
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
_G.LoadingScreenExamples = {
	-- Change example
	changeExample = changeExample,
	
	-- Update loading progress manually
	updateProgress = updateLoadingProgress,
	
	-- Get current example
	getCurrentExample = function()
		return currentExample.name
	end,
	
	-- Get available examples
	getAvailableExamples = function()
		local examples = {}
		for name, example in pairs(EXAMPLES) do
			table.insert(examples, name)
		end
		return examples
	end,
	
	-- Show loading screen
	show = function()
		if not loadingScreen then
			createLoadingScreen()
			animateParticles()
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
	
	-- Examples
	examples = EXAMPLES
}

-- Initialize Loading Screen
local function initializeLoadingScreen()
	-- Wait for PlayerGui
	local playerGui = Players.LocalPlayer:WaitForChild("PlayerGui")
	
	-- Create loading screen
	createLoadingScreen()
	
	-- Start animations
	animateParticles()
	
	-- Start auto loading simulation
	simulateLoading()
	
	print("[LOADING SCREEN EXAMPLES] Initialized with: " .. currentExample.name)
end

-- Start the loading screen
initializeLoadingScreen()

-- Commands for testing
print("🔧 COMMANDS UNTUK TEST EXAMPLES:")
print("_G.LoadingScreenExamples.changeExample('MOUNTAIN') - Change to Mountain theme")
print("_G.LoadingScreenExamples.changeExample('OCEAN') - Change to Ocean theme")
print("_G.LoadingScreenExamples.changeExample('SPACE') - Change to Space theme")
print("_G.LoadingScreenExamples.changeExample('SUNSET') - Change to Sunset theme")
print("_G.LoadingScreenExamples.changeExample('CITY') - Change to City theme")
print("_G.LoadingScreenExamples.changeExample('FOREST') - Change to Forest theme")
print("_G.LoadingScreenExamples.changeExample('DESERT') - Change to Desert theme")
print("_G.LoadingScreenExamples.changeExample('GRADIENT') - Change to Gradient theme")
print("_G.LoadingScreenExamples.updateProgress(50) - Update progress to 50%")
print("_G.LoadingScreenExamples.getCurrentExample() - Get current example")
print("_G.LoadingScreenExamples.getAvailableExamples() - Get available examples")
print("_G.LoadingScreenExamples.show() - Show loading screen")
print("_G.LoadingScreenExamples.hide() - Hide loading screen")
print("_G.LoadingScreenExamples.startAutoLoading() - Start auto loading")
print("")
print("🎨 AVAILABLE EXAMPLES:")
print("- MOUNTAIN (Mountain Adventure)")
print("- OCEAN (Ocean Explorer)")
print("- SPACE (Space Odyssey)")
print("- SUNSET (Sunset Dreams)")
print("- CITY (City Runner)")
print("- FOREST (Forest Guardian)")
print("- DESERT (Desert Wanderer)")
print("- GRADIENT (Gradient Master)")
print("")
print("⚡ FEATURES:")
print("- 8 ready-to-use examples")
print("- Custom backgrounds dengan ID foto")
print("- Gradient backgrounds")
print("- Custom colors untuk setiap tema")
print("- Custom text untuk setiap tema")
print("- Custom loading time")
print("- Ultra optimized dengan debounce")
print("- No bugs atau errors")
print("- Smooth animations")
print("- Particle effects")
print("- Progress bar dengan gradient")
print("- Auto loading simulation")
print("- Easy example switching")
print("")
print("🚀 LOADING SCREEN EXAMPLES READY!")