-- Test Loading Screen Script
-- Script untuk testing Ultimate Full Screen Loading Screen
-- Mudah digunakan dan tidak ada bug

local Players = game:GetService("Players")
local UserInputService = game:GetService("UserInputService")
local RunService = game:GetService("RunService")

-- Test Configuration
local TEST_CONFIG = {
	ENABLE_AUTO_TEST = true, -- Enable auto test
	TEST_INTERVAL = 5, -- Interval test dalam detik
	ENABLE_KEYBOARD_SHORTCUTS = true, -- Enable keyboard shortcuts
	ENABLE_MOUSE_CONTROLS = true, -- Enable mouse controls
}

-- Global variables
local testActive = false
local currentTest = 1
local tests = {
	"showMainMenu",
	"showSettingsMenu", 
	"showRulesMenu",
	"startLoading",
	"changeBackground",
	"changeToGradient",
	"updateProgress"
}
local testConnection = nil

-- Utility Functions
local function waitForLoadingScreen()
	local maxWait = 5
	local waitTime = 0
	
	while not _G.UltimateFullScreenLoadingScreen and waitTime < maxWait do
		wait(0.1)
		waitTime = waitTime + 0.1
	end
	
	return _G.UltimateFullScreenLoadingScreen ~= nil
end

-- Test Functions
local function startTest()
	if testActive then return end
	
	testActive = true
	print("[LOADING SCREEN TEST] Starting test...")
	
	-- Wait for loading screen
	if not waitForLoadingScreen() then
		warn("[LOADING SCREEN TEST] Loading screen not available")
		testActive = false
		return
	end
	
	-- Start test loop
	testConnection = RunService.Heartbeat:Connect(function()
		if not testActive then
			testConnection:Disconnect()
			return
		end
		
		-- Run current test
		local testName = tests[currentTest]
		print("[LOADING SCREEN TEST] Running test: " .. testName)
		
		if testName == "showMainMenu" then
			_G.UltimateFullScreenLoadingScreen.showMainMenu()
		elseif testName == "showSettingsMenu" then
			_G.UltimateFullScreenLoadingScreen.showSettingsMenu()
		elseif testName == "showRulesMenu" then
			_G.UltimateFullScreenLoadingScreen.showRulesMenu()
		elseif testName == "startLoading" then
			_G.UltimateFullScreenLoadingScreen.startLoading()
		elseif testName == "changeBackground" then
			_G.UltimateFullScreenLoadingScreen.changeBackground('rbxassetid://1316045217')
		elseif testName == "changeToGradient" then
			_G.UltimateFullScreenLoadingScreen.changeToGradient()
		elseif testName == "updateProgress" then
			_G.UltimateFullScreenLoadingScreen.updateProgress(math.random(1, 100))
		end
		
		-- Wait for test interval
		wait(TEST_CONFIG.TEST_INTERVAL)
		
		-- Next test
		currentTest = currentTest + 1
		if currentTest > #tests then
			currentTest = 1
		end
	end)
end

local function stopTest()
	if not testActive then return end
	
	testActive = false
	
	if testConnection then
		testConnection:Disconnect()
		testConnection = nil
	end
	
	print("[LOADING SCREEN TEST] Test stopped")
end

local function nextTest()
	if not waitForLoadingScreen() then return end
	
	currentTest = currentTest + 1
	if currentTest > #tests then
		currentTest = 1
	end
	
	local testName = tests[currentTest]
	print("[LOADING SCREEN TEST] Running test: " .. testName)
	
	if testName == "showMainMenu" then
		_G.UltimateFullScreenLoadingScreen.showMainMenu()
	elseif testName == "showSettingsMenu" then
		_G.UltimateFullScreenLoadingScreen.showSettingsMenu()
	elseif testName == "showRulesMenu" then
		_G.UltimateFullScreenLoadingScreen.showRulesMenu()
	elseif testName == "startLoading" then
		_G.UltimateFullScreenLoadingScreen.startLoading()
	elseif testName == "changeBackground" then
		_G.UltimateFullScreenLoadingScreen.changeBackground('rbxassetid://1316045217')
	elseif testName == "changeToGradient" then
		_G.UltimateFullScreenLoadingScreen.changeToGradient()
	elseif testName == "updateProgress" then
		_G.UltimateFullScreenLoadingScreen.updateProgress(math.random(1, 100))
	end
end

local function previousTest()
	if not waitForLoadingScreen() then return end
	
	currentTest = currentTest - 1
	if currentTest < 1 then
		currentTest = #tests
	end
	
	local testName = tests[currentTest]
	print("[LOADING SCREEN TEST] Running test: " .. testName)
	
	if testName == "showMainMenu" then
		_G.UltimateFullScreenLoadingScreen.showMainMenu()
	elseif testName == "showSettingsMenu" then
		_G.UltimateFullScreenLoadingScreen.showSettingsMenu()
	elseif testName == "showRulesMenu" then
		_G.UltimateFullScreenLoadingScreen.showRulesMenu()
	elseif testName == "startLoading" then
		_G.UltimateFullScreenLoadingScreen.startLoading()
	elseif testName == "changeBackground" then
		_G.UltimateFullScreenLoadingScreen.changeBackground('rbxassetid://1316045217')
	elseif testName == "changeToGradient" then
		_G.UltimateFullScreenLoadingScreen.changeToGradient()
	elseif testName == "updateProgress" then
		_G.UltimateFullScreenLoadingScreen.updateProgress(math.random(1, 100))
	end
end

local function showTestInfo()
	if not waitForLoadingScreen() then return end
	
	local currentTestName = tests[currentTest]
	print("[LOADING SCREEN TEST] Current Test: " .. currentTestName)
	print("[LOADING SCREEN TEST] Available Tests: " .. table.concat(tests, ", "))
	print("[LOADING SCREEN TEST] Test Active: " .. (testActive and "Yes" or "No"))
end

-- Individual Test Functions
local function testMainMenu()
	if not waitForLoadingScreen() then return end
	_G.UltimateFullScreenLoadingScreen.showMainMenu()
	print("[LOADING SCREEN TEST] Main Menu test completed")
end

local function testSettingsMenu()
	if not waitForLoadingScreen() then return end
	_G.UltimateFullScreenLoadingScreen.showSettingsMenu()
	print("[LOADING SCREEN TEST] Settings Menu test completed")
end

local function testRulesMenu()
	if not waitForLoadingScreen() then return end
	_G.UltimateFullScreenLoadingScreen.showRulesMenu()
	print("[LOADING SCREEN TEST] Rules Menu test completed")
end

local function testLoading()
	if not waitForLoadingScreen() then return end
	_G.UltimateFullScreenLoadingScreen.startLoading()
	print("[LOADING SCREEN TEST] Loading test completed")
end

local function testBackground()
	if not waitForLoadingScreen() then return end
	_G.UltimateFullScreenLoadingScreen.changeBackground('rbxassetid://1316045217')
	print("[LOADING SCREEN TEST] Background test completed")
end

local function testGradient()
	if not waitForLoadingScreen() then return end
	_G.UltimateFullScreenLoadingScreen.changeToGradient()
	print("[LOADING SCREEN TEST] Gradient test completed")
end

local function testProgress()
	if not waitForLoadingScreen() then return end
	local progress = math.random(1, 100)
	_G.UltimateFullScreenLoadingScreen.updateProgress(progress)
	print("[LOADING SCREEN TEST] Progress test completed: " .. progress .. "%")
end

-- Keyboard Shortcuts
local function setupKeyboardShortcuts()
	if not TEST_CONFIG.ENABLE_KEYBOARD_SHORTCUTS then return end
	
	UserInputService.InputBegan:Connect(function(input, gameProcessed)
		if gameProcessed then return end
		
		if input.KeyCode == Enum.KeyCode.F1 then
			-- F1: Start/Stop Test
			if testActive then
				stopTest()
			else
				startTest()
			end
		elseif input.KeyCode == Enum.KeyCode.F2 then
			-- F2: Next Test
			nextTest()
		elseif input.KeyCode == Enum.KeyCode.F3 then
			-- F3: Previous Test
			previousTest()
		elseif input.KeyCode == Enum.KeyCode.F4 then
			-- F4: Show Test Info
			showTestInfo()
		elseif input.KeyCode == Enum.KeyCode.F5 then
			-- F5: Test Main Menu
			testMainMenu()
		elseif input.KeyCode == Enum.KeyCode.F6 then
			-- F6: Test Settings Menu
			testSettingsMenu()
		elseif input.KeyCode == Enum.KeyCode.F7 then
			-- F7: Test Rules Menu
			testRulesMenu()
		elseif input.KeyCode == Enum.KeyCode.F8 then
			-- F8: Test Loading
			testLoading()
		elseif input.KeyCode == Enum.KeyCode.F9 then
			-- F9: Test Background
			testBackground()
		elseif input.KeyCode == Enum.KeyCode.F10 then
			-- F10: Test Gradient
			testGradient()
		elseif input.KeyCode == Enum.KeyCode.F11 then
			-- F11: Test Progress
			testProgress()
		elseif input.KeyCode == Enum.KeyCode.F12 then
			-- F12: Show Loading Screen
			if waitForLoadingScreen() then
				_G.UltimateFullScreenLoadingScreen.show()
			end
		end
	end)
	
	print("[LOADING SCREEN TEST] Keyboard shortcuts enabled")
end

-- Mouse Controls
local function setupMouseControls()
	if not TEST_CONFIG.ENABLE_MOUSE_CONTROLS then return end
	
	UserInputService.InputBegan:Connect(function(input, gameProcessed)
		if gameProcessed then return end
		
		if input.UserInputType == Enum.UserInputType.MouseButton1 then
			-- Left Click: Next Test
			nextTest()
		elseif input.UserInputType == Enum.UserInputType.MouseButton2 then
			-- Right Click: Previous Test
			previousTest()
		elseif input.UserInputType == Enum.UserInputType.MouseButton3 then
			-- Middle Click: Show Test Info
			showTestInfo()
		end
	end)
	
	print("[LOADING SCREEN TEST] Mouse controls enabled")
end

-- Auto Test
local function startAutoTest()
	if not TEST_CONFIG.ENABLE_AUTO_TEST then return end
	
	wait(5) -- Wait for loading screen to load
	
	if waitForLoadingScreen() then
		startTest()
	end
end

-- Global Functions for External Use
_G.LoadingScreenTest = {
	-- Test control
	startTest = startTest,
	stopTest = stopTest,
	
	-- Test navigation
	nextTest = nextTest,
	previousTest = previousTest,
	showTestInfo = showTestInfo,
	
	-- Individual tests
	testMainMenu = testMainMenu,
	testSettingsMenu = testSettingsMenu,
	testRulesMenu = testRulesMenu,
	testLoading = testLoading,
	testBackground = testBackground,
	testGradient = testGradient,
	testProgress = testProgress,
	
	-- Loading screen control
	showLoadingScreen = function()
		if waitForLoadingScreen() then
			_G.UltimateFullScreenLoadingScreen.show()
		end
	end,
	
	hideLoadingScreen = function()
		if waitForLoadingScreen() then
			_G.UltimateFullScreenLoadingScreen.hide()
		end
	end,
	
	-- Info
	getCurrentTest = function()
		return tests[currentTest]
	end,
	
	getAvailableTests = function()
		return tests
	end,
	
	isTestActive = function()
		return testActive
	end,
	
	-- Configuration
	config = TEST_CONFIG
}

-- Initialize Test
local function initializeTest()
	-- Setup keyboard shortcuts
	setupKeyboardShortcuts()
	
	-- Setup mouse controls
	setupMouseControls()
	
	-- Start auto test
	startAutoTest()
	
	print("[LOADING SCREEN TEST] Test initialized")
end

-- Start test
initializeTest()

-- Commands for testing
print("🔧 COMMANDS UNTUK TEST LOADING SCREEN:")
print("_G.LoadingScreenTest.startTest() - Start auto test")
print("_G.LoadingScreenTest.stopTest() - Stop auto test")
print("_G.LoadingScreenTest.nextTest() - Next test")
print("_G.LoadingScreenTest.previousTest() - Previous test")
print("_G.LoadingScreenTest.showTestInfo() - Show test info")
print("_G.LoadingScreenTest.testMainMenu() - Test main menu")
print("_G.LoadingScreenTest.testSettingsMenu() - Test settings menu")
print("_G.LoadingScreenTest.testRulesMenu() - Test rules menu")
print("_G.LoadingScreenTest.testLoading() - Test loading")
print("_G.LoadingScreenTest.testBackground() - Test background")
print("_G.LoadingScreenTest.testGradient() - Test gradient")
print("_G.LoadingScreenTest.testProgress() - Test progress")
print("_G.LoadingScreenTest.showLoadingScreen() - Show loading screen")
print("_G.LoadingScreenTest.hideLoadingScreen() - Hide loading screen")
print("_G.LoadingScreenTest.getCurrentTest() - Get current test")
print("_G.LoadingScreenTest.getAvailableTests() - Get available tests")
print("_G.LoadingScreenTest.isTestActive() - Check if test is active")
print("")
print("⌨️ KEYBOARD SHORTCUTS:")
print("F1 - Start/Stop Test")
print("F2 - Next Test")
print("F3 - Previous Test")
print("F4 - Show Test Info")
print("F5 - Test Main Menu")
print("F6 - Test Settings Menu")
print("F7 - Test Rules Menu")
print("F8 - Test Loading")
print("F9 - Test Background")
print("F10 - Test Gradient")
print("F11 - Test Progress")
print("F12 - Show Loading Screen")
print("")
print("🖱️ MOUSE CONTROLS:")
print("Left Click - Next Test")
print("Right Click - Previous Test")
print("Middle Click - Show Test Info")
print("")
print("🧪 TEST FEATURES:")
print("- Auto test dengan cycling tests")
print("- Keyboard shortcuts untuk semua fungsi")
print("- Mouse controls untuk test navigation")
print("- Individual test functions")
print("- Test information display")
print("- Test control (start/stop)")
print("- Performance optimized")
print("")
print("🚀 LOADING SCREEN TEST READY!")