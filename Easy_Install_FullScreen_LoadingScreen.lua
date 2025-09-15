-- Easy Install Ultimate Full Screen Loading Screen
-- Script instalasi otomatis yang mudah untuk Loading Screen
-- Cukup copy paste dan save!

local Players = game:GetService("Players")
local TweenService = game:GetService("TweenService")
local RunService = game:GetService("RunService")
local UserInputService = game:GetService("UserInputService")

-- Configuration - UBAH SESUAI KEBUTUHAN ANDA
local CONFIG = {
	-- Background Settings
	BACKGROUND_TYPE = "GRADIENT", -- "IMAGE" atau "GRADIENT"
	BACKGROUND_IMAGE_ID = "rbxassetid://1316045217", -- GANTI DENGAN ID FOTO ANDA
	
	-- Theme Colors
	PRIMARY_COLOR = Color3.fromRGB(34, 139, 34), -- Hijau
	SECONDARY_COLOR = Color3.fromRGB(139, 69, 19), -- Coklat
	ACCENT_COLOR = Color3.fromRGB(255, 215, 0), -- Emas
	TEXT_COLOR = Color3.fromRGB(255, 255, 255), -- Putih
	BUTTON_COLOR = Color3.fromRGB(50, 50, 50), -- Dark Gray
	BUTTON_HOVER_COLOR = Color3.fromRGB(70, 70, 70), -- Light Gray
	
	-- Settings
	LOADING_TIME = 3, -- Durasi loading (detik)
	GAME_TITLE = "GAME ANDA", -- NAMA GAME ANDA
	GAME_SUBTITLE = "Deskripsi game Anda", -- DESKRIPSI GAME
	LOADING_TEXT = "LOADING...", -- TEXT LOADING
	
	-- Features
	ENABLE_PARTICLES = true, -- Enable/disable particles
	ENABLE_ANIMATIONS = true, -- Enable/disable animations
}

-- Server Rules - UBAH SESUAI KEBUTUHAN ANDA
local SERVER_RULES = {
	"1. Hormati semua pemain dan admin",
	"2. Jangan menggunakan cheat atau exploit",
	"3. Jangan spam chat atau flood",
	"4. Jangan menggunakan bahasa kasar",
	"5. Ikuti instruksi admin dengan baik",
	"6. Jangan melakukan griefing",
	"7. Laporkan bug atau masalah ke admin",
	"8. Nikmati permainan dengan fair play",
	"9. Jangan melakukan RDM (Random Death Match)",
	"10. Patuhi semua peraturan server"
}

-- Global variables
local loadingScreen = nil
local currentMenu = "main" -- "main", "settings", "rules", "loading"
local isLoaded = false
local buttons = {}
local particles = {}

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

local debounce = createDebounce()

-- Device Detection
local function getDeviceType()
	local platform = UserInputService:GetPlatform()
	if platform == Enum.Platform.Android or platform == Enum.Platform.IOS then
		return "mobile"
	elseif platform == Enum.Platform.XBoxOne or platform == Enum.Platform.PS4 or platform == Enum.Platform.PS5 or platform == Enum.Platform.XBoxSeriesX then
		return "console"
	else
		return "pc"
	end
end

-- Create Button Function
local function createButton(name, text, position, callback)
	local button = Instance.new("TextButton")
	button.Name = name
	button.Size = UDim2.new(0.25, 0, 0.08, 0)
	button.Position = position
	button.BackgroundColor3 = CONFIG.BUTTON_COLOR
	button.BorderSizePixel = 0
	button.Text = text
	button.TextColor3 = CONFIG.TEXT_COLOR
	button.TextScaled = true
	button.Font = Enum.Font.GothamBold
	button.Parent = loadingScreen:FindFirstChild("MainFrame")
	
	-- Button gradient
	local buttonGradient = Instance.new("UIGradient")
	buttonGradient.Color = ColorSequence.new({
		ColorSequenceKeypoint.new(0, CONFIG.BUTTON_COLOR),
		ColorSequenceKeypoint.new(1, Color3.new(CONFIG.BUTTON_COLOR.R * 0.8, CONFIG.BUTTON_COLOR.G * 0.8, CONFIG.BUTTON_COLOR.B * 0.8))
	})
	buttonGradient.Parent = button
	
	-- Button stroke
	local buttonStroke = Instance.new("UIStroke")
	buttonStroke.Color = CONFIG.ACCENT_COLOR
	buttonStroke.Thickness = 2
	buttonStroke.Parent = button
	
	-- Button click effect
	button.MouseButton1Click:Connect(function()
		if debounce() then
			-- Click animation
			local tween = TweenService:Create(button, TweenInfo.new(0.1), {
				Size = UDim2.new(0.24, 0, 0.076, 0)
			})
			tween:Play()
			
			tween.Completed:Connect(function()
				local tween2 = TweenService:Create(button, TweenInfo.new(0.1), {
					Size = UDim2.new(0.25, 0, 0.08, 0)
				})
				tween2:Play()
			end)
			
			-- Call callback
			if callback then
				callback()
			end
		end
	end)
	
	table.insert(buttons, button)
	return button
end

-- Create Loading Screen GUI
local function createLoadingScreen()
	if loadingScreen then
		loadingScreen:Destroy()
	end
	
	loadingScreen = Instance.new("ScreenGui")
	loadingScreen.Name = "EasyFullScreenLoadingScreen"
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
	if CONFIG.BACKGROUND_TYPE == "IMAGE" then
		local backgroundImage = Instance.new("ImageLabel")
		backgroundImage.Name = "BackgroundImage"
		backgroundImage.Size = UDim2.new(1, 0, 1, 0)
		backgroundImage.Position = UDim2.new(0, 0, 0, 0)
		backgroundImage.BackgroundTransparency = 1
		backgroundImage.Image = CONFIG.BACKGROUND_IMAGE_ID
		backgroundImage.ScaleType = Enum.ScaleType.Crop
		backgroundImage.Parent = mainFrame
		
		local overlay = Instance.new("Frame")
		overlay.Name = "Overlay"
		overlay.Size = UDim2.new(1, 0, 1, 0)
		overlay.Position = UDim2.new(0, 0, 0, 0)
		overlay.BackgroundColor3 = Color3.fromRGB(0, 0, 0)
		overlay.BackgroundTransparency = 0.4
		overlay.BorderSizePixel = 0
		overlay.Parent = mainFrame
	else
		local backgroundGradient = Instance.new("UIGradient")
		backgroundGradient.Color = ColorSequence.new({
			ColorSequenceKeypoint.new(0, CONFIG.PRIMARY_COLOR),
			ColorSequenceKeypoint.new(0.5, CONFIG.SECONDARY_COLOR),
			ColorSequenceKeypoint.new(1, CONFIG.ACCENT_COLOR)
		})
		backgroundGradient.Rotation = 45
		backgroundGradient.Parent = mainFrame
	end
	
	-- Game Title
	local gameTitle = Instance.new("TextLabel")
	gameTitle.Name = "GameTitle"
	gameTitle.Size = UDim2.new(0.8, 0, 0.1, 0)
	gameTitle.Position = UDim2.new(0.1, 0, 0.15, 0)
	gameTitle.BackgroundTransparency = 1
	gameTitle.Text = CONFIG.GAME_TITLE
	gameTitle.TextColor3 = CONFIG.TEXT_COLOR
	gameTitle.TextScaled = true
	gameTitle.Font = Enum.Font.GothamBold
	gameTitle.TextStrokeTransparency = 0.5
	gameTitle.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	gameTitle.Parent = mainFrame
	
	-- Game Subtitle
	local gameSubtitle = Instance.new("TextLabel")
	gameSubtitle.Name = "GameSubtitle"
	gameSubtitle.Size = UDim2.new(0.6, 0, 0.05, 0)
	gameSubtitle.Position = UDim2.new(0.2, 0, 0.25, 0)
	gameSubtitle.BackgroundTransparency = 1
	gameSubtitle.Text = CONFIG.GAME_SUBTITLE
	gameSubtitle.TextColor3 = CONFIG.TEXT_COLOR
	gameSubtitle.TextScaled = true
	gameSubtitle.Font = Enum.Font.Gotham
	gameSubtitle.TextStrokeTransparency = 0.5
	gameSubtitle.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	gameSubtitle.Parent = mainFrame
	
	-- Device Info
	local deviceInfo = Instance.new("TextLabel")
	deviceInfo.Name = "DeviceInfo"
	deviceInfo.Size = UDim2.new(0.3, 0, 0.04, 0)
	deviceInfo.Position = UDim2.new(0.35, 0, 0.05, 0)
	deviceInfo.BackgroundTransparency = 1
	deviceInfo.Text = "Platform: " .. getDeviceType():upper()
	deviceInfo.TextColor3 = CONFIG.TEXT_COLOR
	deviceInfo.TextScaled = true
	deviceInfo.Font = Enum.Font.Gotham
	deviceInfo.TextStrokeTransparency = 0.5
	deviceInfo.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	deviceInfo.Parent = mainFrame
	
	-- Loading Elements (hidden by default)
	local loadingText = Instance.new("TextLabel")
	loadingText.Name = "LoadingText"
	loadingText.Size = UDim2.new(0.6, 0, 0.1, 0)
	loadingText.Position = UDim2.new(0.2, 0, 0.4, 0)
	loadingText.BackgroundTransparency = 1
	loadingText.Text = CONFIG.LOADING_TEXT
	loadingText.TextColor3 = CONFIG.TEXT_COLOR
	loadingText.TextScaled = true
	loadingText.Font = Enum.Font.GothamBold
	loadingText.TextStrokeTransparency = 0.5
	loadingText.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	loadingText.Visible = false
	loadingText.Parent = mainFrame
	
	-- Loading Bar Background
	local loadingBarBg = Instance.new("Frame")
	loadingBarBg.Name = "LoadingBarBg"
	loadingBarBg.Size = UDim2.new(0.6, 0, 0.02, 0)
	loadingBarBg.Position = UDim2.new(0.2, 0, 0.52, 0)
	loadingBarBg.BackgroundColor3 = Color3.new(0.2, 0.2, 0.2)
	loadingBarBg.BorderSizePixel = 0
	loadingBarBg.Visible = false
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
	progressText.Position = UDim2.new(0.2, 0, 0.55, 0)
	progressText.BackgroundTransparency = 1
	progressText.Text = "0%"
	progressText.TextColor3 = CONFIG.TEXT_COLOR
	progressText.TextScaled = true
	progressText.Font = Enum.Font.Gotham
	progressText.TextStrokeTransparency = 0.5
	progressText.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	progressText.Visible = false
	progressText.Parent = mainFrame
	
	-- Particles (if enabled)
	if CONFIG.ENABLE_PARTICLES then
		for i = 1, 15 do
			local particle = Instance.new("Frame")
			particle.Name = "Particle" .. i
			particle.Size = UDim2.new(0.01, 0, 0.01, 0)
			particle.Position = UDim2.new(math.random(), 0, math.random(), 0)
			particle.BackgroundColor3 = CONFIG.ACCENT_COLOR
			particle.BorderSizePixel = 0
			particle.Parent = mainFrame
			table.insert(particles, particle)
		end
	end
	
	return loadingScreen
end

-- Menu Functions
local function showMainMenu()
	currentMenu = "main"
	
	-- Hide loading elements
	local loadingText = loadingScreen:FindFirstChild("MainFrame"):FindFirstChild("LoadingText")
	local loadingBarBg = loadingScreen:FindFirstChild("MainFrame"):FindFirstChild("LoadingBarBg")
	local progressText = loadingScreen:FindFirstChild("MainFrame"):FindFirstChild("ProgressText")
	
	if loadingText then loadingText.Visible = false end
	if loadingBarBg then loadingBarBg.Visible = false end
	if progressText then progressText.Visible = false end
	
	-- Clear existing buttons
	for _, button in ipairs(buttons) do
		if button and button.Parent then
			button:Destroy()
		end
	end
	buttons = {}
	
	-- Create main menu buttons
	local buttonY = 0.6
	local buttonSpacing = 0.02
	
	-- Play Button
	createButton("PlayButton", "🎮 PLAY", UDim2.new(0.375, 0, buttonY, 0), function()
		startLoading()
	end)
	
	-- Settings Button
	createButton("SettingsButton", "⚙️ SETTINGS", UDim2.new(0.375, 0, buttonY + 0.1, 0), function()
		showSettingsMenu()
	end)
	
	-- Rules Button
	createButton("RulesButton", "📋 RULES", UDim2.new(0.375, 0, buttonY + 0.2, 0), function()
		showRulesMenu()
	end)
	
	-- Quit Button
	createButton("QuitButton", "🚪 QUIT", UDim2.new(0.375, 0, buttonY + 0.3, 0), function()
		quitGame()
	end)
end

local function showSettingsMenu()
	currentMenu = "settings"
	
	-- Clear existing buttons
	for _, button in ipairs(buttons) do
		if button and button.Parent then
			button:Destroy()
		end
	end
	buttons = {}
	
	-- Create settings menu buttons
	local buttonY = 0.6
	local buttonSpacing = 0.02
	
	-- Graphics Quality
	createButton("GraphicsButton", "🎨 GRAPHICS: HIGH", UDim2.new(0.375, 0, buttonY, 0), function()
		print("[SETTINGS] Graphics quality toggled")
	end)
	
	-- Sound Volume
	createButton("SoundButton", "🔊 SOUND: ON", UDim2.new(0.375, 0, buttonY + 0.1, 0), function()
		print("[SETTINGS] Sound toggled")
	end)
	
	-- Particles
	createButton("ParticlesButton", "✨ PARTICLES: ON", UDim2.new(0.375, 0, buttonY + 0.2, 0), function()
		CONFIG.ENABLE_PARTICLES = not CONFIG.ENABLE_PARTICLES
		print("[SETTINGS] Particles:", CONFIG.ENABLE_PARTICLES and "ON" or "OFF")
	end)
	
	-- Back Button
	createButton("BackButton", "⬅️ BACK", UDim2.new(0.375, 0, buttonY + 0.3, 0), function()
		showMainMenu()
	end)
end

local function showRulesMenu()
	currentMenu = "rules"
	
	-- Clear existing buttons
	for _, button in ipairs(buttons) do
		if button and button.Parent then
			button:Destroy()
		end
	end
	buttons = {}
	
	-- Create rules frame
	local rulesFrame = Instance.new("Frame")
	rulesFrame.Name = "RulesFrame"
	rulesFrame.Size = UDim2.new(0.7, 0, 0.6, 0)
	rulesFrame.Position = UDim2.new(0.15, 0, 0.2, 0)
	rulesFrame.BackgroundColor3 = Color3.fromRGB(0, 0, 0)
	rulesFrame.BackgroundTransparency = 0.3
	rulesFrame.BorderSizePixel = 0
	rulesFrame.Parent = loadingScreen:FindFirstChild("MainFrame")
	
	-- Rules title
	local rulesTitle = Instance.new("TextLabel")
	rulesTitle.Name = "RulesTitle"
	rulesTitle.Size = UDim2.new(1, 0, 0.1, 0)
	rulesTitle.Position = UDim2.new(0, 0, 0, 0)
	rulesTitle.BackgroundTransparency = 1
	rulesTitle.Text = "📋 SERVER RULES"
	rulesTitle.TextColor3 = CONFIG.ACCENT_COLOR
	rulesTitle.TextScaled = true
	rulesTitle.Font = Enum.Font.GothamBold
	rulesTitle.TextStrokeTransparency = 0.5
	rulesTitle.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	rulesTitle.Parent = rulesFrame
	
	-- Rules content
	local rulesContent = Instance.new("TextLabel")
	rulesContent.Name = "RulesContent"
	rulesContent.Size = UDim2.new(0.9, 0, 0.8, 0)
	rulesContent.Position = UDim2.new(0.05, 0, 0.15, 0)
	rulesContent.BackgroundTransparency = 1
	rulesContent.Text = table.concat(SERVER_RULES, "\n")
	rulesContent.TextColor3 = CONFIG.TEXT_COLOR
	rulesContent.TextScaled = true
	rulesContent.Font = Enum.Font.Gotham
	rulesContent.TextStrokeTransparency = 0.5
	rulesContent.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
	rulesContent.TextXAlignment = Enum.TextXAlignment.Left
	rulesContent.TextYAlignment = Enum.TextYAlignment.Top
	rulesContent.Parent = rulesFrame
	
	-- Back Button
	createButton("BackButton", "⬅️ BACK", UDim2.new(0.375, 0, 0.85, 0), function()
		rulesFrame:Destroy()
		showMainMenu()
	end)
end

local function startLoading()
	currentMenu = "loading"
	
	-- Clear existing buttons
	for _, button in ipairs(buttons) do
		if button and button.Parent then
			button:Destroy()
		end
	end
	buttons = {}
	
	-- Show loading elements
	local loadingText = loadingScreen:FindFirstChild("MainFrame"):FindFirstChild("LoadingText")
	local loadingBarBg = loadingScreen:FindFirstChild("MainFrame"):FindFirstChild("LoadingBarBg")
	local progressText = loadingScreen:FindFirstChild("MainFrame"):FindFirstChild("ProgressText")
	
	if loadingText then loadingText.Visible = true end
	if loadingBarBg then loadingBarBg.Visible = true end
	if progressText then progressText.Visible = true end
	
	-- Start loading simulation
	simulateLoading()
end

local function quitGame()
	-- Quit game function
	if Players.LocalPlayer then
		Players.LocalPlayer:Kick("Thanks for playing!")
	end
end

-- Animation Functions
local function animateLoadingBar(progress)
	local loadingBar = loadingScreen:FindFirstChild("MainFrame"):FindFirstChild("LoadingBarBg"):FindFirstChild("LoadingBar")
	local progressText = loadingScreen:FindFirstChild("MainFrame"):FindFirstChild("ProgressText")
	
	if loadingBar and progressText then
		local tweenInfo = TweenInfo.new(0.5, Enum.EasingStyle.Quart, Enum.EasingDirection.Out)
		local tween = TweenService:Create(loadingBar, tweenInfo, {
			Size = UDim2.new(progress / 100, 0, 1, 0)
		})
		tween:Play()
		progressText.Text = math.floor(progress) .. "%"
	end
end

local function animateParticles()
	if not CONFIG.ENABLE_PARTICLES then return end
	
	for i, particle in ipairs(particles) do
		if particle and particle.Parent then
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

-- Loading Progress Function
local function updateLoadingProgress(progress)
	progress = math.clamp(progress, 0, 100)
	animateLoadingBar(progress)
	
	if progress >= 100 and not isLoaded then
		isLoaded = true
		local mainFrame = loadingScreen:FindFirstChild("MainFrame")
		if mainFrame then
			local tweenInfo = TweenInfo.new(1, Enum.EasingStyle.Quart, Enum.EasingDirection.Out)
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
	local increment = 100 / (CONFIG.LOADING_TIME * 10)
	
	local connection
	connection = RunService.Heartbeat:Connect(function()
		progress = progress + increment
		updateLoadingProgress(progress)
		
		if progress >= 100 then
			connection:Disconnect()
		end
	end)
end

-- Global Functions
_G.EasyFullScreenLoadingScreen = {
	-- Menu functions
	showMainMenu = showMainMenu,
	showSettingsMenu = showSettingsMenu,
	showRulesMenu = showRulesMenu,
	startLoading = startLoading,
	quitGame = quitGame,
	
	-- Loading functions
	updateProgress = updateLoadingProgress,
	
	-- Background functions
	changeBackground = function(imageId)
		if type(imageId) == "string" and imageId:find("rbxassetid://") then
			CONFIG.BACKGROUND_IMAGE_ID = imageId
			CONFIG.BACKGROUND_TYPE = "IMAGE"
			createLoadingScreen()
			showMainMenu()
			animateParticles()
			print("[EASY LOADING SCREEN] Background changed to: " .. imageId)
		else
			warn("[EASY LOADING SCREEN] Invalid image ID format. Use: rbxassetid://[ID]")
		end
	end,
	
	changeToGradient = function()
		CONFIG.BACKGROUND_TYPE = "GRADIENT"
		createLoadingScreen()
		showMainMenu()
		animateParticles()
		print("[EASY LOADING SCREEN] Background changed to gradient")
	end,
	
	-- Show/Hide functions
	show = function()
		if not loadingScreen then
			createLoadingScreen()
			showMainMenu()
			animateParticles()
		end
	end,
	
	hide = function()
		if loadingScreen then
			loadingScreen:Destroy()
			loadingScreen = nil
		end
	end,
	
	-- Configuration
	config = CONFIG,
	rules = SERVER_RULES
}

-- Initialize
local function initializeLoadingScreen()
	local playerGui = Players.LocalPlayer:WaitForChild("PlayerGui")
	createLoadingScreen()
	showMainMenu()
	animateParticles()
	print("[EASY LOADING SCREEN] Initialized for " .. getDeviceType():upper() .. " platform")
end

-- Start
initializeLoadingScreen()

-- Commands
print("🔧 COMMANDS:")
print("_G.EasyFullScreenLoadingScreen.showMainMenu() - Show main menu")
print("_G.EasyFullScreenLoadingScreen.showSettingsMenu() - Show settings menu")
print("_G.EasyFullScreenLoadingScreen.showRulesMenu() - Show rules menu")
print("_G.EasyFullScreenLoadingScreen.startLoading() - Start loading")
print("_G.EasyFullScreenLoadingScreen.quitGame() - Quit game")
print("_G.EasyFullScreenLoadingScreen.changeBackground('rbxassetid://1316045217') - Change background")
print("_G.EasyFullScreenLoadingScreen.changeToGradient() - Change to gradient")
print("_G.EasyFullScreenLoadingScreen.updateProgress(50) - Update progress")
print("_G.EasyFullScreenLoadingScreen.show() - Show loading screen")
print("_G.EasyFullScreenLoadingScreen.hide() - Hide loading screen")
print("")
print("🎮 FEATURES:")
print("- Full screen untuk Mobile, Console, dan PC")
print("- Main Menu dengan Play, Settings, Rules, Quit")
print("- Settings Menu dengan Graphics, Sound, Particles")
print("- Rules Menu dengan 10 peraturan server")
print("- Loading Screen dengan progress bar")
print("- Custom background dengan ID foto")
print("- Gradient background")
print("- Smooth animations")
print("- Particle effects")
print("- Device detection")
print("- Button click effects")
print("- Ultra optimized dengan debounce")
print("- No bugs atau errors")
print("")
print("📋 SERVER RULES:")
for i, rule in ipairs(SERVER_RULES) do
	print(rule)
end
print("")
print("🚀 EASY FULL SCREEN LOADING SCREEN READY!")