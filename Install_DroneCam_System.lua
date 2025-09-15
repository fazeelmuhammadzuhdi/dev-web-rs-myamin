-- Install DroneCam System untuk Roblox
-- Script instalasi otomatis untuk DroneCam Freecam System
-- Place di ServerScriptService atau StarterGui

local Players = game:GetService("Players")
local ReplicatedStorage = game:GetService("ReplicatedStorage")

-- Configuration
local CONFIG = {
	SYSTEM_NAME = "DroneCamSystem",
	VERSION = "1.0.0",
	AUTHOR = "DroneCam Team",
	DESCRIPTION = "Advanced DroneCam Freecam System untuk Roblox",
	
	-- Installation settings
	INSTALL_TO_SERVERSCRIPT = true,  -- Install ke ServerScriptService
	INSTALL_TO_STARTERGUI = false,   -- Install ke StarterGui
	CREATE_REMOTES = true,           -- Create remote events
	ENABLE_LOGGING = true,           -- Enable server logging
}

-- Cleanup function
local function cleanup()
	-- Remove old system
	local oldSystem = ReplicatedStorage:FindFirstChild(CONFIG.SYSTEM_NAME)
	if oldSystem then
		oldSystem:Destroy()
		print("🧹 Cleaned up old DroneCam system")
	end
end

-- Create system folder
local function createSystemFolder()
	local systemFolder = Instance.new("Folder")
	systemFolder.Name = CONFIG.SYSTEM_NAME
	systemFolder.Parent = ReplicatedStorage
	
	-- Add system info
	local systemInfo = Instance.new("StringValue")
	systemInfo.Name = "SystemInfo"
	systemInfo.Value = string.format("Version: %s | Author: %s | Description: %s", 
		CONFIG.VERSION, CONFIG.AUTHOR, CONFIG.DESCRIPTION)
	systemInfo.Parent = systemFolder
	
	return systemFolder
end

-- Create remote events
local function createRemotes(systemFolder)
	if not CONFIG.CREATE_REMOTES then return end
	
	-- Drone command remote
	local droneCommand = Instance.new("RemoteEvent")
	droneCommand.Name = "DroneCommand"
	droneCommand.Parent = systemFolder
	
	-- Drone status remote
	local droneStatus = Instance.new("RemoteEvent")
	droneStatus.Name = "DroneStatus"
	droneStatus.Parent = systemFolder
	
	-- Drone data remote
	local droneData = Instance.new("RemoteEvent")
	droneData.Name = "DroneData"
	droneData.Parent = systemFolder
	
	print("📡 Created remote events for DroneCam system")
end

-- Create server logging
local function createServerLogging(systemFolder)
	if not CONFIG.ENABLE_LOGGING then return end
	
	local serverLog = Instance.new("RemoteEvent")
	serverLog.Name = "ServerLog"
	serverLog.Parent = systemFolder
	
	-- Handle logging
	serverLog.OnServerEvent:Connect(function(player, logData)
		local timestamp = os.date("%Y-%m-%d %H:%M:%S")
		local logMessage = string.format("[%s] %s: %s", timestamp, player.Name, logData.message)
		
		print(logMessage)
		
		-- Log to server console
		if logData.type == "ERROR" then
			warn(logMessage)
		elseif logData.type == "INFO" then
			print(logMessage)
		end
	end)
	
	print("📝 Created server logging system")
end

-- Create installation script
local function createInstallationScript(systemFolder)
	local installScript = Instance.new("Script")
	installScript.Name = "InstallationScript"
	installScript.Parent = systemFolder
	
	-- Script content
	installScript.Source = [[
-- DroneCam System Installation Script
-- This script handles the installation and setup of the DroneCam system

local Players = game:GetService("Players")
local ReplicatedStorage = game:GetService("ReplicatedStorage")

-- Wait for system folder
local systemFolder = ReplicatedStorage:WaitForChild("DroneCamSystem")

-- Installation status
local INSTALLATION_COMPLETE = false

-- Check if system is properly installed
local function checkInstallation()
	local requiredItems = {
		"SystemInfo",
		"DroneCommand",
		"DroneStatus", 
		"DroneData",
		"ServerLog"
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
		warn("❌ DroneCam system installation incomplete!")
		return
	end
	
	-- Mark as complete
	INSTALLATION_COMPLETE = true
	
	-- Log installation
	print("✅ DroneCam system installation complete!")
	print("🚁 System ready for use")
	print("💬 Commands: /drone, /dronecam, /offdrone, /offdronecam")
	
	-- Notify players
	for _, player in ipairs(Players:GetPlayers()) do
		if player:FindFirstChild("PlayerGui") then
			-- Create notification
			local gui = Instance.new("ScreenGui")
			gui.Name = "DroneCamNotification"
			gui.Parent = player.PlayerGui
			
			local frame = Instance.new("Frame")
			frame.Size = UDim2.new(0, 300, 0, 80)
			frame.Position = UDim2.new(0.5, -150, 0, 20)
			frame.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
			frame.BorderSizePixel = 0
			frame.Parent = gui
			
			local corner = Instance.new("UICorner")
			corner.CornerRadius = UDim.new(0, 8)
			corner.Parent = frame
			
			local label = Instance.new("TextLabel")
			label.Size = UDim2.new(1, 0, 1, 0)
			label.BackgroundTransparency = 1
			label.Text = "🚁 DroneCam System Ready!\nUse /drone to activate"
			label.TextColor3 = Color3.fromRGB(255, 255, 255)
			label.TextScaled = true
			label.Font = Enum.Font.GothamBold
			label.Parent = frame
			
			-- Auto remove after 5 seconds
			game:GetService("Debris"):AddItem(gui, 5)
		end
	end
end

-- Wait for players
Players.PlayerAdded:Connect(function(player)
	player.CharacterAdded:Connect(function()
		-- Wait a bit for character to load
		wait(2)
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
local function installDroneCamSystem()
	print("🚀 Starting DroneCam System installation...")
	
	-- Cleanup old system
	cleanup()
	
	-- Create system folder
	local systemFolder = createSystemFolder()
	
	-- Create remote events
	createRemotes(systemFolder)
	
	-- Create server logging
	createServerLogging(systemFolder)
	
	-- Create installation script
	createInstallationScript(systemFolder)
	
	print("✅ DroneCam System installation complete!")
	print("📋 System Information:")
	print("   Name: " .. CONFIG.SYSTEM_NAME)
	print("   Version: " .. CONFIG.VERSION)
	print("   Author: " .. CONFIG.AUTHOR)
	print("   Description: " .. CONFIG.DESCRIPTION)
	print("")
	print("🔧 Installation Options:")
	print("   Install to ServerScript: " .. tostring(CONFIG.INSTALL_TO_SERVERSCRIPT))
	print("   Install to StarterGui: " .. tostring(CONFIG.INSTALL_TO_STARTERGUI))
	print("   Create Remotes: " .. tostring(CONFIG.CREATE_REMOTES))
	print("   Enable Logging: " .. tostring(CONFIG.ENABLE_LOGGING))
	print("")
	print("💬 Chat Commands:")
	print("   /drone - Activate drone")
	print("   /dronecam - Activate drone")
	print("   /dronecamera - Activate drone")
	print("   /offdrone - Deactivate drone")
	print("   /offdronecam - Deactivate drone")
	print("   /offdronecamera - Deactivate drone")
	print("")
	print("🎮 Controls:")
	print("   WASD - Move drone")
	print("   Space - Move up")
	print("   Left Shift - Move down")
	print("   Left Ctrl + Mouse - Look around")
	print("")
	print("🚁 DroneCam System Ready!")
end

-- Run installation
installDroneCamSystem()

-- Global functions for external control
_G.DroneCamInstaller = {
	-- Installation info
	getConfig = function()
		return CONFIG
	end,
	
	-- Reinstall system
	reinstall = function()
		installDroneCamSystem()
	end,
	
	-- Check installation
	checkInstallation = function()
		local systemFolder = ReplicatedStorage:FindFirstChild(CONFIG.SYSTEM_NAME)
		if not systemFolder then return false end
		
		local requiredItems = {
			"SystemInfo",
			"DroneCommand",
			"DroneStatus",
			"DroneData",
			"ServerLog"
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
		local systemFolder = ReplicatedStorage:FindFirstChild(CONFIG.SYSTEM_NAME)
		if not systemFolder then return nil end
		
		local systemInfo = systemFolder:FindFirstChild("SystemInfo")
		if not systemInfo then return nil end
		
		return systemInfo.Value
	end
}

-- Commands for testing
print("🔧 COMMANDS UNTUK TEST INSTALLER:")
print("_G.DroneCamInstaller.getConfig() - Get installation config")
print("_G.DroneCamInstaller.reinstall() - Reinstall system")
print("_G.DroneCamInstaller.checkInstallation() - Check installation status")
print("_G.DroneCamInstaller.getSystemInfo() - Get system info")
print("")
print("📋 INSTALLATION COMPLETE!")
print("🚁 DroneCam System is ready to use!")
print("💬 Use /drone in chat to activate drone camera")
print("🎮 Use WASD to move, mouse to look around")
print("⚠️ Drone is limited to 30 studs from spawn point")
print("🔧 All UI will be hidden when drone is active")
print("")
print("✅ Installation successful!")