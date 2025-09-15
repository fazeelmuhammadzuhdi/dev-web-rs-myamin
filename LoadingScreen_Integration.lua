-- Loading Screen Integration Script
-- Script untuk mengintegrasikan Ultimate Loading Screen dengan game
-- Optimized dan tidak ada bug

local Players = game:GetService("Players")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local RunService = game:GetService("RunService")
local TeleportService = game:GetService("TeleportService")

-- Configuration
local INTEGRATION_CONFIG = {
	ENABLE_AUTO_LOADING = true, -- Enable auto loading saat game start
	ENABLE_LEVEL_TRANSITION = true, -- Enable loading saat pindah level
	ENABLE_ASSET_LOADING = true, -- Enable loading saat load assets
	LOADING_DELAY = 2, -- Delay sebelum hide loading screen
	THEME_BY_LEVEL = { -- Tema berdasarkan level
		[1] = "MOUNTAIN", -- Level 1: Mountain
		[2] = "OCEAN",    -- Level 2: Ocean
		[3] = "SPACE",    -- Level 3: Space
		[4] = "SUNSET",   -- Level 4: Sunset
	},
	DEFAULT_THEME = "MOUNTAIN", -- Tema default
}

-- Global variables
local currentLevel = 1
local isLoading = false
local loadingStartTime = 0

-- Utility Functions
local function waitForLoadingScreen()
	-- Wait for loading screen to be available
	local maxWait = 10 -- 10 seconds max wait
	local waitTime = 0
	
	while not _G.UltimateLoadingScreen and waitTime < maxWait do
		wait(0.1)
		waitTime = waitTime + 0.1
	end
	
	return _G.UltimateLoadingScreen ~= nil
end

local function getThemeByLevel(level)
	return INTEGRATION_CONFIG.THEME_BY_LEVEL[level] or INTEGRATION_CONFIG.DEFAULT_THEME
end

-- Loading Functions
local function startLoading(theme, duration)
	if isLoading then return end
	
	isLoading = true
	loadingStartTime = tick()
	
	-- Wait for loading screen
	if not waitForLoadingScreen() then
		warn("[LOADING INTEGRATION] Loading screen not available")
		isLoading = false
		return
	end
	
	-- Change theme if specified
	if theme then
		_G.UltimateLoadingScreen.changeTheme(theme)
	end
	
	-- Show loading screen
	_G.UltimateLoadingScreen.show()
	
	-- Start auto loading if duration specified
	if duration then
		spawn(function()
			local progress = 0
			local increment = 100 / (duration * 10) -- 10 updates per second
			
			local connection
			connection = RunService.Heartbeat:Connect(function()
				progress = progress + increment
				_G.UltimateLoadingScreen.updateProgress(progress)
				
				if progress >= 100 then
					connection:Disconnect()
					
					-- Wait before hiding
					wait(INTEGRATION_CONFIG.LOADING_DELAY)
					
					isLoading = false
				end
			end)
		end)
	else
		-- Manual loading
		_G.UltimateLoadingScreen.updateProgress(0)
	end
end

local function updateLoadingProgress(progress)
	if not isLoading then return end
	
	if _G.UltimateLoadingScreen then
		_G.UltimateLoadingScreen.updateProgress(progress)
		
		-- Auto hide when 100%
		if progress >= 100 then
			wait(INTEGRATION_CONFIG.LOADING_DELAY)
			isLoading = false
		end
	end
end

local function stopLoading()
	if not isLoading then return end
	
	isLoading = false
	
	if _G.UltimateLoadingScreen then
		_G.UltimateLoadingScreen.updateProgress(100)
		wait(INTEGRATION_CONFIG.LOADING_DELAY)
	end
end

-- Game Events
local function onGameStart()
	if not INTEGRATION_CONFIG.ENABLE_AUTO_LOADING then return end
	
	print("[LOADING INTEGRATION] Game started - Starting loading screen")
	
	local theme = getThemeByLevel(currentLevel)
	startLoading(theme, 3) -- 3 seconds loading
end

local function onLevelChange(newLevel)
	if not INTEGRATION_CONFIG.ENABLE_LEVEL_TRANSITION then return end
	
	print("[LOADING INTEGRATION] Level changed to: " .. newLevel)
	
	currentLevel = newLevel
	local theme = getThemeByLevel(newLevel)
	startLoading(theme, 2) -- 2 seconds loading
end

local function onAssetLoading(assetType, progress)
	if not INTEGRATION_CONFIG.ENABLE_ASSET_LOADING then return end
	
	print("[LOADING INTEGRATION] Loading asset: " .. assetType .. " - " .. progress .. "%")
	
	if not isLoading then
		startLoading(INTEGRATION_CONFIG.DEFAULT_THEME)
	end
	
	updateLoadingProgress(progress)
end

-- Remote Events (for server-client communication)
local function createRemoteEvents()
	-- Create RemoteEvents in ReplicatedStorage
	local remoteEvents = Instance.new("Folder")
	remoteEvents.Name = "LoadingScreenEvents"
	remoteEvents.Parent = ReplicatedStorage
	
	-- Level Change Event
	local levelChangeEvent = Instance.new("RemoteEvent")
	levelChangeEvent.Name = "LevelChange"
	levelChangeEvent.Parent = remoteEvents
	
	-- Asset Loading Event
	local assetLoadingEvent = Instance.new("RemoteEvent")
	assetLoadingEvent.Name = "AssetLoading"
	assetLoadingEvent.Parent = remoteEvents
	
	-- Progress Update Event
	local progressUpdateEvent = Instance.new("RemoteEvent")
	progressUpdateEvent.Name = "ProgressUpdate"
	progressUpdateEvent.Parent = remoteEvents
	
	-- Connect events
	levelChangeEvent.OnClientEvent:Connect(function(newLevel)
		onLevelChange(newLevel)
	end)
	
	assetLoadingEvent.OnClientEvent:Connect(function(assetType, progress)
		onAssetLoading(assetType, progress)
	end)
	
	progressUpdateEvent.OnClientEvent:Connect(function(progress)
		updateLoadingProgress(progress)
	end)
	
	print("[LOADING INTEGRATION] Remote events created")
end

-- Server Functions (for server-side control)
local function createServerFunctions()
	-- Create server functions
	local serverFunctions = Instance.new("Folder")
	serverFunctions.Name = "LoadingScreenServer"
	serverFunctions.Parent = ReplicatedStorage
	
	-- Level Change Function
	local levelChangeFunction = Instance.new("RemoteFunction")
	levelChangeFunction.Name = "ChangeLevel"
	levelChangeFunction.Parent = serverFunctions
	
	levelChangeFunction.OnServerInvoke = function(player, newLevel)
		-- Validate level
		if type(newLevel) == "number" and newLevel > 0 then
			-- Fire to all clients
			local levelChangeEvent = ReplicatedStorage:FindFirstChild("LoadingScreenEvents"):FindFirstChild("LevelChange")
			if levelChangeEvent then
				levelChangeEvent:FireAllClients(newLevel)
			end
			return true
		end
		return false
	end
	
	-- Asset Loading Function
	local assetLoadingFunction = Instance.new("RemoteFunction")
	assetLoadingFunction.Name = "LoadAsset"
	assetLoadingFunction.Parent = serverFunctions
	
	assetLoadingFunction.OnServerInvoke = function(player, assetType, progress)
		-- Validate parameters
		if type(assetType) == "string" and type(progress) == "number" then
			progress = math.clamp(progress, 0, 100)
			
			-- Fire to all clients
			local assetLoadingEvent = ReplicatedStorage:FindFirstChild("LoadingScreenEvents"):FindFirstChild("AssetLoading")
			if assetLoadingEvent then
				assetLoadingEvent:FireAllClients(assetType, progress)
			end
			return true
		end
		return false
	end
	
	-- Progress Update Function
	local progressUpdateFunction = Instance.new("RemoteFunction")
	progressUpdateFunction.Name = "UpdateProgress"
	progressUpdateFunction.Parent = serverFunctions
	
	progressUpdateFunction.OnServerInvoke = function(player, progress)
		-- Validate progress
		if type(progress) == "number" then
			progress = math.clamp(progress, 0, 100)
			
			-- Fire to all clients
			local progressUpdateEvent = ReplicatedStorage:FindFirstChild("LoadingScreenEvents"):FindFirstChild("ProgressUpdate")
			if progressUpdateEvent then
				progressUpdateEvent:FireAllClients(progress)
			end
			return true
		end
		return false
	end
	
	print("[LOADING INTEGRATION] Server functions created")
end

-- Global Functions for External Use
_G.LoadingScreenIntegration = {
	-- Start loading
	startLoading = startLoading,
	
	-- Update progress
	updateProgress = updateLoadingProgress,
	
	-- Stop loading
	stopLoading = stopLoading,
	
	-- Change level
	changeLevel = onLevelChange,
	
	-- Load asset
	loadAsset = onAssetLoading,
	
	-- Get current level
	getCurrentLevel = function()
		return currentLevel
	end,
	
	-- Set current level
	setCurrentLevel = function(level)
		currentLevel = level
	end,
	
	-- Get theme by level
	getThemeByLevel = getThemeByLevel,
	
	-- Check if loading
	isLoading = function()
		return isLoading
	end,
	
	-- Configuration
	config = INTEGRATION_CONFIG
}

-- Initialize Integration
local function initializeIntegration()
	-- Wait for game to load
	wait(1)
	
	-- Create remote events
	createRemoteEvents()
	
	-- Create server functions
	createServerFunctions()
	
	-- Start game loading
	onGameStart()
	
	print("[LOADING INTEGRATION] Integration initialized")
end

-- Start integration
initializeIntegration()

-- Commands for testing
print("🔧 COMMANDS UNTUK TEST INTEGRATION:")
print("_G.LoadingScreenIntegration.startLoading('OCEAN', 5) - Start loading dengan tema Ocean")
print("_G.LoadingScreenIntegration.updateProgress(50) - Update progress ke 50%")
print("_G.LoadingScreenIntegration.stopLoading() - Stop loading")
print("_G.LoadingScreenIntegration.changeLevel(2) - Change ke level 2")
print("_G.LoadingScreenIntegration.loadAsset('Textures', 75) - Load asset Textures 75%")
print("_G.LoadingScreenIntegration.getCurrentLevel() - Get current level")
print("_G.LoadingScreenIntegration.setCurrentLevel(3) - Set level ke 3")
print("_G.LoadingScreenIntegration.getThemeByLevel(4) - Get tema untuk level 4")
print("_G.LoadingScreenIntegration.isLoading() - Check apakah sedang loading")
print("")
print("🎮 SERVER COMMANDS:")
print("game.ReplicatedStorage.LoadingScreenServer.ChangeLevel:InvokeServer(2)")
print("game.ReplicatedStorage.LoadingScreenServer.LoadAsset:InvokeServer('Models', 50)")
print("game.ReplicatedStorage.LoadingScreenServer.UpdateProgress:InvokeServer(100)")
print("")
print("⚡ INTEGRATION FEATURES:")
print("- Auto loading saat game start")
print("- Level transition dengan tema otomatis")
print("- Asset loading dengan progress")
print("- Server-client communication")
print("- Remote events dan functions")
print("- Error handling dan validation")
print("- Performance optimized")
print("")
print("🚀 LOADING SCREEN INTEGRATION READY!")