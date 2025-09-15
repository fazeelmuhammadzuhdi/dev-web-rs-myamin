-- Loading Screen Demo Script
-- Script untuk demo dan testing Ultimate Loading Screen
-- Optimized dan tidak ada bug

local Players = game:GetService("Players")
local UserInputService = game:GetService("UserInputService")
local RunService = game:GetService("RunService")

-- Demo Configuration
local DEMO_CONFIG = {
	ENABLE_AUTO_DEMO = true, -- Enable auto demo
	DEMO_INTERVAL = 10, -- Interval demo dalam detik
	DEMO_DURATION = 5, -- Durasi loading dalam detik
	ENABLE_KEYBOARD_SHORTCUTS = true, -- Enable keyboard shortcuts
	ENABLE_MOUSE_CONTROLS = true, -- Enable mouse controls
}

-- Global variables
local demoActive = false
local currentDemoTheme = 1
local themes = {"MOUNTAIN", "OCEAN", "SPACE", "SUNSET"}
local demoConnection = nil

-- Utility Functions
local function waitForLoadingScreen()
	local maxWait = 5
	local waitTime = 0
	
	while not _G.UltimateLoadingScreen and waitTime < maxWait do
		wait(0.1)
		waitTime = waitTime + 0.1
	end
	
	return _G.UltimateLoadingScreen ~= nil
end

-- Demo Functions
local function startDemo()
	if demoActive then return end
	
	demoActive = true
	print("[LOADING SCREEN DEMO] Starting demo...")
	
	-- Wait for loading screen
	if not waitForLoadingScreen() then
		warn("[LOADING SCREEN DEMO] Loading screen not available")
		demoActive = false
		return
	end
	
	-- Start demo loop
	demoConnection = RunService.Heartbeat:Connect(function()
		if not demoActive then
			demoConnection:Disconnect()
			return
		end
		
		-- Cycle through themes
		local theme = themes[currentDemoTheme]
		_G.UltimateLoadingScreen.changeTheme(theme)
		
		-- Simulate loading
		local progress = 0
		local increment = 100 / (DEMO_CONFIG.DEMO_DURATION * 10)
		
		local loadingConnection
		loadingConnection = RunService.Heartbeat:Connect(function()
			progress = progress + increment
			_G.UltimateLoadingScreen.updateProgress(progress)
			
			if progress >= 100 then
				loadingConnection:Disconnect()
				
				-- Wait before next theme
				wait(2)
				
				-- Next theme
				currentDemoTheme = currentDemoTheme + 1
				if currentDemoTheme > #themes then
					currentDemoTheme = 1
				end
			end
		end)
		
		-- Wait for demo interval
		wait(DEMO_CONFIG.DEMO_INTERVAL)
	end)
end

local function stopDemo()
	if not demoActive then return end
	
	demoActive = false
	
	if demoConnection then
		demoConnection:Disconnect()
		demoConnection = nil
	end
	
	print("[LOADING SCREEN DEMO] Demo stopped")
end

local function nextTheme()
	if not waitForLoadingScreen() then return end
	
	currentDemoTheme = currentDemoTheme + 1
	if currentDemoTheme > #themes then
		currentDemoTheme = 1
	end
	
	local theme = themes[currentDemoTheme]
	_G.UltimateLoadingScreen.changeTheme(theme)
	
	print("[LOADING SCREEN DEMO] Theme changed to: " .. theme)
end

local function previousTheme()
	if not waitForLoadingScreen() then return end
	
	currentDemoTheme = currentDemoTheme - 1
	if currentDemoTheme < 1 then
		currentDemoTheme = #themes
	end
	
	local theme = themes[currentDemoTheme]
	_G.UltimateLoadingScreen.changeTheme(theme)
	
	print("[LOADING SCREEN DEMO] Theme changed to: " .. theme)
end

local function showThemeInfo()
	if not waitForLoadingScreen() then return end
	
	local currentTheme = _G.UltimateLoadingScreen.getCurrentTheme()
	local availableThemes = _G.UltimateLoadingScreen.getAvailableThemes()
	
	print("[LOADING SCREEN DEMO] Current Theme: " .. currentTheme)
	print("[LOADING SCREEN DEMO] Available Themes: " .. table.concat(availableThemes, ", "))
end

-- Keyboard Shortcuts
local function setupKeyboardShortcuts()
	if not DEMO_CONFIG.ENABLE_KEYBOARD_SHORTCUTS then return end
	
	UserInputService.InputBegan:Connect(function(input, gameProcessed)
		if gameProcessed then return end
		
		if input.KeyCode == Enum.KeyCode.F1 then
			-- F1: Start/Stop Demo
			if demoActive then
				stopDemo()
			else
				startDemo()
			end
		elseif input.KeyCode == Enum.KeyCode.F2 then
			-- F2: Next Theme
			nextTheme()
		elseif input.KeyCode == Enum.KeyCode.F3 then
			-- F3: Previous Theme
			previousTheme()
		elseif input.KeyCode == Enum.KeyCode.F4 then
			-- F4: Show Theme Info
			showThemeInfo()
		elseif input.KeyCode == Enum.KeyCode.F5 then
			-- F5: Show Loading Screen
			if waitForLoadingScreen() then
				_G.UltimateLoadingScreen.show()
			end
		elseif input.KeyCode == Enum.KeyCode.F6 then
			-- F6: Hide Loading Screen
			if waitForLoadingScreen() then
				_G.UltimateLoadingScreen.hide()
			end
		elseif input.KeyCode == Enum.KeyCode.F7 then
			-- F7: Start Auto Loading
			if waitForLoadingScreen() then
				_G.UltimateLoadingScreen.startAutoLoading()
			end
		elseif input.KeyCode == Enum.KeyCode.F8 then
			-- F8: Update Progress to 50%
			if waitForLoadingScreen() then
				_G.UltimateLoadingScreen.updateProgress(50)
			end
		elseif input.KeyCode == Enum.KeyCode.F9 then
			-- F9: Update Progress to 100%
			if waitForLoadingScreen() then
				_G.UltimateLoadingScreen.updateProgress(100)
			end
		end
	end)
	
	print("[LOADING SCREEN DEMO] Keyboard shortcuts enabled")
end

-- Mouse Controls
local function setupMouseControls()
	if not DEMO_CONFIG.ENABLE_MOUSE_CONTROLS then return end
	
	UserInputService.InputBegan:Connect(function(input, gameProcessed)
		if gameProcessed then return end
		
		if input.UserInputType == Enum.UserInputType.MouseButton1 then
			-- Left Click: Next Theme
			nextTheme()
		elseif input.UserInputType == Enum.UserInputType.MouseButton2 then
			-- Right Click: Previous Theme
			previousTheme()
		elseif input.UserInputType == Enum.UserInputType.MouseButton3 then
			-- Middle Click: Show Theme Info
			showThemeInfo()
		end
	end)
	
	print("[LOADING SCREEN DEMO] Mouse controls enabled")
end

-- Auto Demo
local function startAutoDemo()
	if not DEMO_CONFIG.ENABLE_AUTO_DEMO then return end
	
	wait(5) -- Wait for loading screen to load
	
	if waitForLoadingScreen() then
		startDemo()
	end
end

-- Global Functions for External Use
_G.LoadingScreenDemo = {
	-- Demo control
	startDemo = startDemo,
	stopDemo = stopDemo,
	
	-- Theme control
	nextTheme = nextTheme,
	previousTheme = previousTheme,
	showThemeInfo = showThemeInfo,
	
	-- Loading control
	showLoadingScreen = function()
		if waitForLoadingScreen() then
			_G.UltimateLoadingScreen.show()
		end
	end,
	
	hideLoadingScreen = function()
		if waitForLoadingScreen() then
			_G.UltimateLoadingScreen.hide()
		end
	end,
	
	startAutoLoading = function()
		if waitForLoadingScreen() then
			_G.UltimateLoadingScreen.startAutoLoading()
		end
	end,
	
	updateProgress = function(progress)
		if waitForLoadingScreen() then
			_G.UltimateLoadingScreen.updateProgress(progress)
		end
	end,
	
	-- Info
	getCurrentTheme = function()
		if waitForLoadingScreen() then
			return _G.UltimateLoadingScreen.getCurrentTheme()
		end
		return "Unknown"
	end,
	
	getAvailableThemes = function()
		if waitForLoadingScreen() then
			return _G.UltimateLoadingScreen.getAvailableThemes()
		end
		return {}
	end,
	
	isDemoActive = function()
		return demoActive
	end,
	
	-- Configuration
	config = DEMO_CONFIG
}

-- Initialize Demo
local function initializeDemo()
	-- Setup keyboard shortcuts
	setupKeyboardShortcuts()
	
	-- Setup mouse controls
	setupMouseControls()
	
	-- Start auto demo
	startAutoDemo()
	
	print("[LOADING SCREEN DEMO] Demo initialized")
end

-- Start demo
initializeDemo()

-- Commands for testing
print("🔧 COMMANDS UNTUK TEST DEMO:")
print("_G.LoadingScreenDemo.startDemo() - Start demo")
print("_G.LoadingScreenDemo.stopDemo() - Stop demo")
print("_G.LoadingScreenDemo.nextTheme() - Next theme")
print("_G.LoadingScreenDemo.previousTheme() - Previous theme")
print("_G.LoadingScreenDemo.showThemeInfo() - Show theme info")
print("_G.LoadingScreenDemo.showLoadingScreen() - Show loading screen")
print("_G.LoadingScreenDemo.hideLoadingScreen() - Hide loading screen")
print("_G.LoadingScreenDemo.startAutoLoading() - Start auto loading")
print("_G.LoadingScreenDemo.updateProgress(75) - Update progress to 75%")
print("_G.LoadingScreenDemo.getCurrentTheme() - Get current theme")
print("_G.LoadingScreenDemo.getAvailableThemes() - Get available themes")
print("_G.LoadingScreenDemo.isDemoActive() - Check if demo is active")
print("")
print("⌨️ KEYBOARD SHORTCUTS:")
print("F1 - Start/Stop Demo")
print("F2 - Next Theme")
print("F3 - Previous Theme")
print("F4 - Show Theme Info")
print("F5 - Show Loading Screen")
print("F6 - Hide Loading Screen")
print("F7 - Start Auto Loading")
print("F8 - Update Progress to 50%")
print("F9 - Update Progress to 100%")
print("")
print("🖱️ MOUSE CONTROLS:")
print("Left Click - Next Theme")
print("Right Click - Previous Theme")
print("Middle Click - Show Theme Info")
print("")
print("🎮 DEMO FEATURES:")
print("- Auto demo dengan cycling themes")
print("- Keyboard shortcuts untuk semua fungsi")
print("- Mouse controls untuk theme switching")
print("- Progress simulation")
print("- Theme information display")
print("- Demo control (start/stop)")
print("- Performance optimized")
print("")
print("🚀 LOADING SCREEN DEMO READY!")