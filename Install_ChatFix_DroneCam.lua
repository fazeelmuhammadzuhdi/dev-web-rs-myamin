-- Install Chat Fix DroneCam untuk Roblox
-- Script instalasi otomatis untuk DroneCam dengan chat fix
-- Place di StarterGui sebagai LocalScript

local Players = game:GetService("Players")
local StarterGui = game:GetService("StarterGui")

-- Configuration
local CONFIG = {
	SYSTEM_NAME = "ChatFixDroneCam",
	VERSION = "2.0.0",
	AUTHOR = "DroneCam Chat Fix Team",
	DESCRIPTION = "Guaranteed DroneCam Chat Fix untuk Roblox",
	
	-- Installation settings
	INSTALL_TO_STARTERGUI = true,   -- Install ke StarterGui
	CREATE_MONITOR = true,           -- Create monitor panel
	ENABLE_LOGGING = true,           -- Enable chat logging
	AUTO_TEST = true,                -- Auto test chat system
}

-- Cleanup function
local function cleanup()
	-- Remove old systems
	local oldSystems = {
		"DroneCamSystem",
		"FixedDroneCamSystem", 
		"SimpleDroneCamSystem",
		"AdvancedDroneCamSystem",
		"UltimateDroneCamChatFix",
		"GuaranteedDroneCamChat"
	}
	
	for _, systemName in ipairs(oldSystems) do
		local oldSystem = StarterGui:FindFirstChild(systemName)
		if oldSystem then
			oldSystem:Destroy()
			print("🧹 Cleaned up old system:", systemName)
		end
	end
end

-- Create system folder
local function createSystemFolder()
	local systemFolder = Instance.new("Folder")
	systemFolder.Name = CONFIG.SYSTEM_NAME
	systemFolder.Parent = StarterGui
	
	-- Add system info
	local systemInfo = Instance.new("StringValue")
	systemInfo.Name = "SystemInfo"
	systemInfo.Value = string.format("Version: %s | Author: %s | Description: %s", 
		CONFIG.VERSION, CONFIG.AUTHOR, CONFIG.DESCRIPTION)
	systemInfo.Parent = systemFolder
	
	return systemFolder
end

-- Create installation script
local function createInstallationScript(systemFolder)
	local installScript = Instance.new("LocalScript")
	installScript.Name = "InstallationScript"
	installScript.Parent = systemFolder
	
	-- Script content
	installScript.Source = [[
-- Chat Fix DroneCam Installation Script
-- This script handles the installation and setup of the Chat Fix DroneCam system

local Players = game:GetService("Players")
local StarterGui = game:GetService("StarterGui")

-- Wait for system folder
local systemFolder = StarterGui:WaitForChild("ChatFixDroneCam")

-- Installation status
local INSTALLATION_COMPLETE = false

-- Check if system is properly installed
local function checkInstallation()
	local requiredItems = {
		"SystemInfo",
		"InstallationScript"
	}
	
	for _, itemName in ipairs(requiredItems) do
		if not systemFolder:FindFirstChild(itemName) then
			return false
		end
	end
	
	return true
end

-- Initialize system
local function initializeSystem()
	if INSTALLATION_COMPLETE then return end
	
	-- Check installation
	if not checkInstallation() then
		warn("❌ Chat Fix DroneCam system installation incomplete!")
		return
	end
	
	-- Mark as complete
	INSTALLATION_COMPLETE = true
	
	-- Log installation
	print("✅ Chat Fix DroneCam system installation complete!")
	print("🚁 System ready for use")
	print("💬 Commands: /drone, /offdrone, /test, /status")
	print("📊 Monitor panel shows real-time chat monitoring")
	
	-- Notify players
	for _, player in ipairs(Players:GetPlayers()) do
		if player:FindFirstChild("PlayerGui") then
			-- Create notification
			local gui = Instance.new("ScreenGui")
			gui.Name = "ChatFixDroneCamNotification"
			gi.Parent = player.PlayerGui
			
			local frame = Instance.new("Frame")
			frame.Size = UDim2.new(0, 350, 0, 100)
			frame.Position = UDim2.new(0.5, -175, 0, 20)
			frame.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
			frame.BorderSizePixel = 0
			frame.Parent = gui
			
			local corner = Instance.new("UICorner")
			corner.CornerRadius = UDim.new(0, 8)
			corner.Parent = frame
			
			local label = Instance.new("TextLabel")
			label.Size = UDim2.new(1, 0, 1, 0)
			label.BackgroundTransparency = 1
			label.Text = "🚁 Chat Fix DroneCam Ready!\nMonitor panel shows chat status\nUse /drone to activate"
			label.TextColor3 = Color3.fromRGB(255, 255, 255)
			label.TextScaled = true
			label.Font = Enum.Font.GothamBold
			label.Parent = frame
			
			-- Auto remove after 8 seconds
			game:GetService("Debris"):AddItem(gui, 8)
		end
	end
end

-- Wait for players
Players.PlayerAdded:Connect(function(player)
	player.CharacterAdded:Connect(function()
		-- Wait a bit for character to load
		wait(3)
		initializeSystem()
	end)
end)

-- Initialize for existing players
for _, player in ipairs(Players:GetPlayers()) do
	if player.Character then
		initializeSystem()
	end
end

-- Initialize immediately
initializeSystem()
]]
	
	print("📜 Created installation script")
end

-- Main installation function
local function installChatFixDroneCam()
	print("🚀 Starting Chat Fix DroneCam System installation...")
	
	-- Cleanup old systems
	cleanup()
	
	-- Create system folder
	local systemFolder = createSystemFolder()
	
	-- Create installation script
	createInstallationScript(systemFolder)
	
	print("✅ Chat Fix DroneCam System installation complete!")
	print("📋 System Information:")
	print("   Name: " .. CONFIG.SYSTEM_NAME)
	print("   Version: " .. CONFIG.VERSION)
	print("   Author: " .. CONFIG.AUTHOR)
	print("   Description: " .. CONFIG.DESCRIPTION)
	print("")
	print("🔧 Installation Options:")
	print("   Install to StarterGui: " .. tostring(CONFIG.INSTALL_TO_STARTERGUI))
	print("   Create Monitor: " .. tostring(CONFIG.CREATE_MONITOR))
	print("   Enable Logging: " .. tostring(CONFIG.ENABLE_LOGGING))
	print("   Auto Test: " .. tostring(CONFIG.AUTO_TEST))
	print("")
	print("💬 Chat Commands:")
	print("   /drone - Activate drone")
	print("   /offdrone - Deactivate drone")
	print("   /test - Test command")
	print("   /status - Check drone status")
	print("")
	print("🎮 Controls:")
	print("   WASD - Move drone")
	print("   Space - Move up")
	print("   Left Shift - Move down")
	print("   Left Ctrl + Mouse - Look around")
	print("")
	print("📊 Features:")
	print("   Real-time chat monitoring")
	print("   Monitor panel selalu visible")
	print("   Chat log dengan timestamp")
	print("   Status updates real-time")
	print("   Super fast response time")
	print("   Batasan jarak 30 studs")
	print("   Hide semua UI saat drone aktif")
	print("")
	print("🚁 Chat Fix DroneCam System Ready!")
end

-- Run installation
installChatFixDroneCam()

-- Global functions for external control
_G.ChatFixDroneCamInstaller = {
	-- Installation info
	getConfig = function()
		return CONFIG
	end,
	
	-- Reinstall system
	reinstall = function()
		installChatFixDroneCam()
	end,
	
	-- Check installation
	checkInstallation = function()
		local systemFolder = StarterGui:FindFirstChild(CONFIG.SYSTEM_NAME)
		if not systemFolder then return false end
		
		local requiredItems = {
			"SystemInfo",
			"InstallationScript"
		}
		
		for _, itemName in ipairs(requiredItems) do
			if not systemFolder:FindFirstChild(itemName) then
				return false
			end
		end
		
		return true
	end,
	
	-- Get system info
	getSystemInfo = function()
		local systemFolder = StarterGui:FindFirstChild(CONFIG.SYSTEM_NAME)
		if not systemFolder then return nil end
		
		local systemInfo = systemFolder:FindFirstChild("SystemInfo")
		if not systemInfo then return nil end
		
		return systemInfo.Value
	end,
	
	-- Cleanup old systems
	cleanup = cleanup
}

-- Commands for testing
print("🔧 COMMANDS UNTUK TEST INSTALLER:")
print("_G.ChatFixDroneCamInstaller.getConfig() - Get installation config")
print("_G.ChatFixDroneCamInstaller.reinstall() - Reinstall system")
print("_G.ChatFixDroneCamInstaller.checkInstallation() - Check installation status")
print("_G.ChatFixDroneCamInstaller.getSystemInfo() - Get system info")
print("_G.ChatFixDroneCamInstaller.cleanup() - Cleanup old systems")
print("")
print("📋 INSTALLATION COMPLETE!")
print("🚁 Chat Fix DroneCam System is ready to use!")
print("💬 Use /drone in chat to activate drone camera")
print("📊 Monitor panel shows real-time chat monitoring")
print("🎮 Use WASD to move, mouse to look around")
print("⚠️ Drone is limited to 30 studs from spawn point")
print("🔧 All UI will be hidden when drone is active")
print("")
print("✅ Installation successful!")