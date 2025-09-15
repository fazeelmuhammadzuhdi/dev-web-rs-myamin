-- Easy Install Loading Screen
-- Script instalasi otomatis yang mudah untuk Loading Screen
-- Cukup copy paste dan save!

local Players = game:GetService("Players")
local TweenService = game:GetService("TweenService")
local RunService = game:GetService("RunService")

-- Configuration - UBAH SESUAI KEBUTUHAN ANDA
local CONFIG = {
	-- Background Settings
	BACKGROUND_TYPE = "IMAGE", -- "IMAGE" atau "GRADIENT"
	BACKGROUND_IMAGE_ID = "rbxassetid://1316045217", -- GANTI DENGAN ID FOTO ANDA
	
	-- Colors
	PRIMARY_COLOR = Color3.fromRGB(34, 139, 34), -- Hijau
	SECONDARY_COLOR = Color3.fromRGB(139, 69, 19), -- Coklat
	ACCENT_COLOR = Color3.fromRGB(255, 215, 0), -- Emas
	TEXT_COLOR = Color3.fromRGB(255, 255, 255), -- Putih
	
	-- Settings
	LOADING_TIME = 5, -- Durasi loading (detik)
	GAME_TITLE = "GAME ANDA", -- NAMA GAME ANDA
	LOADING_TEXT = "LOADING...", -- TEXT LOADING
}

-- Global variables
local loadingScreen = nil
local isLoaded = false

-- Create Loading Screen
local function createLoadingScreen()
	if loadingScreen then
		loadingScreen:Destroy()
	end
	
	loadingScreen = Instance.new("ScreenGui")
	loadingScreen.Name = "EasyLoadingScreen"
	loadingScreen.ResetOnSpawn = false
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
		overlay.BackgroundTransparency = 0.3
		overlay.BorderSizePixel = 0
		overlay.Parent = mainFrame
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
	
	return loadingScreen
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
_G.EasyLoadingScreen = {
	changeBackground = function(imageId)
		if type(imageId) == "string" and imageId:find("rbxassetid://") then
			CONFIG.BACKGROUND_IMAGE_ID = imageId
			CONFIG.BACKGROUND_TYPE = "IMAGE"
			createLoadingScreen()
			print("[EASY LOADING SCREEN] Background changed to: " .. imageId)
		else
			warn("[EASY LOADING SCREEN] Invalid image ID format. Use: rbxassetid://[ID]")
		end
	end,
	
	updateProgress = updateLoadingProgress,
	show = function()
		if not loadingScreen then
			createLoadingScreen()
		end
	end,
	hide = function()
		if loadingScreen then
			loadingScreen:Destroy()
			loadingScreen = nil
		end
	end,
	startAutoLoading = simulateLoading,
	config = CONFIG
}

-- Initialize
local function initializeLoadingScreen()
	local playerGui = Players.LocalPlayer:WaitForChild("PlayerGui")
	createLoadingScreen()
	simulateLoading()
	print("[EASY LOADING SCREEN] Initialized!")
end

-- Start
initializeLoadingScreen()

-- Commands
print("🔧 COMMANDS:")
print("_G.EasyLoadingScreen.changeBackground('rbxassetid://1316045217')")
print("_G.EasyLoadingScreen.updateProgress(50)")
print("_G.EasyLoadingScreen.show()")
print("_G.EasyLoadingScreen.hide()")
print("_G.EasyLoadingScreen.startAutoLoading()")
print("")
print("🚀 EASY LOADING SCREEN READY!")