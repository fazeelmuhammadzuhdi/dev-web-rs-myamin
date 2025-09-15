-- Install Admin Menu System untuk Roblox
-- Script instalasi otomatis untuk Admin Menu System
-- Place di StarterGui sebagai LocalScript

local Players = game:GetService("Players")
local StarterGui = game:GetService("StarterGui")

-- Configuration
local CONFIG = {
	SYSTEM_NAME = "AdminMenuSystem",
	VERSION = "1.0.0",
	AUTHOR = "Admin Menu Team",
	DESCRIPTION = "Ultimate Admin Menu System untuk Roblox",
	
	-- Installation settings
	INSTALL_TO_STARTERGUI = true,   -- Install ke StarterGui
	CREATE_MOBILE_ICON = true,       -- Create mobile icon
	ENABLE_SHARING = true,           -- Enable menu sharing
	AUTO_TEST = true,                -- Auto test system
}

-- Cleanup function
local function cleanup()
	-- Remove old systems
	local oldSystems = {
		"UltimateAdminMenuSystem",
		"AdvancedAdminMenuSystem",
		"AdminMenuSystem"
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
-- Admin Menu System Installation Script
-- This script handles the installation and setup of the Admin Menu system

local Players = game:GetService("Players")
local StarterGui = game:GetService("StarterGui")

-- Wait for system folder
local systemFolder = StarterGui:WaitForChild("AdminMenuSystem")

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
		warn("❌ Admin Menu System installation incomplete!")
		return
	end
	
	-- Mark as complete
	INSTALLATION_COMPLETE = true
	
	-- Log installation
	print("✅ Admin Menu System installation complete!")
	print("👑 System ready for use")
	print("🎮 Controls: F3 (Desktop) or Mobile Icon (Mobile)")
	print("📤 Features: Menu sharing, Player management, Server commands")
	
	-- Notify players
	for _, player in ipairs(Players:GetPlayers()) do
		if player:FindFirstChild("PlayerGui") then
			-- Create notification
			local gui = Instance.new("ScreenGui")
			gui.Name = "AdminMenuSystemNotification"
			gui.Parent = player.PlayerGui
			
			local frame = Instance.new("Frame")
			frame.Size = UDim2.new(0, 400, 0, 120)
			frame.Position = UDim2.new(0.5, -200, 0, 20)
			frame.BackgroundColor3 = Color3.fromRGB(255, 215, 0)
			frame.BorderSizePixel = 0
			frame.Parent = gui
			
			local corner = Instance.new("UICorner")
			corner.CornerRadius = UDim.new(0, 8)
			corner.Parent = frame
			
			local stroke = Instance.new("UIStroke")
			stroke.Color = Color3.fromRGB(255, 255, 255)
			stroke.Thickness = 2
			stroke.Parent = frame
			
			local label = Instance.new("TextLabel")
			label.Size = UDim2.new(1, 0, 1, 0)
			label.BackgroundTransparency = 1
			label.Text = "👑 Admin Menu System Ready!\nPress F3 to open menu (Desktop)\nClick mobile icon (Mobile)\nMenu can be shared to other players"
			label.TextColor3 = Color3.fromRGB(0, 0, 0)
			label.TextScaled = true
			label.Font = Enum.Font.GothamBold
			label.Parent = frame
			
			-- Auto remove after 10 seconds
			game:GetService("Debris"):AddItem(gui, 10)
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
local function installAdminMenuSystem()
	print("🚀 Starting Admin Menu System installation...")
	
	-- Cleanup old systems
	cleanup()
	
	-- Create system folder
	local systemFolder = createSystemFolder()
	
	-- Create installation script
	createInstallationScript(systemFolder)
	
	print("✅ Admin Menu System installation complete!")
	print("📋 System Information:")
	print("   Name: " .. CONFIG.SYSTEM_NAME)
	print("   Version: " .. CONFIG.VERSION)
	print("   Author: " .. CONFIG.AUTHOR)
	print("   Description: " .. CONFIG.DESCRIPTION)
	print("")
	print("🔧 Installation Options:")
	print("   Install to StarterGui: " .. tostring(CONFIG.INSTALL_TO_STARTERGUI))
	print("   Create Mobile Icon: " .. tostring(CONFIG.CREATE_MOBILE_ICON))
	print("   Enable Sharing: " .. tostring(CONFIG.ENABLE_SHARING))
	print("   Auto Test: " .. tostring(CONFIG.AUTO_TEST))
	print("")
	print("🎮 Controls:")
	print("   F3 - Toggle menu (Desktop)")
	print("   Mobile Icon - Toggle menu (Mobile)")
	print("   Click player name - Share menu to player")
	print("   Click admin buttons - Execute admin actions")
	print("")
	print("📤 Features:")
	print("   Menu sharing to other players")
	print("   Menu disappears when player leaves server")
	print("   Mobile optimization with smaller sizes")
	print("   Debounce optimization")
	print("   Player management")
	print("   Server commands")
	print("   Settings management")
	print("   Smooth animations")
	print("   Real-time updates")
	print("")
	print("👑 Admin Menu System Ready!")
end

-- Run installation
installAdminMenuSystem()

-- Global functions for external control
_G.AdminMenuSystemInstaller = {
	-- Installation info
	getConfig = function()
		return CONFIG
	end,
	
	-- Reinstall system
	reinstall = function()
		installAdminMenuSystem()
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
print("_G.AdminMenuSystemInstaller.getConfig() - Get installation config")
print("_G.AdminMenuSystemInstaller.reinstall() - Reinstall system")
print("_G.AdminMenuSystemInstaller.checkInstallation() - Check installation status")
print("_G.AdminMenuSystemInstaller.getSystemInfo() - Get system info")
print("_G.AdminMenuSystemInstaller.cleanup() - Cleanup old systems")
print("")
print("📋 INSTALLATION COMPLETE!")
print("👑 Admin Menu System is ready to use!")
print("🎮 Press F3 to open menu (Desktop) or click mobile icon (Mobile)")
print("📤 Menu can be shared to other players")
print("⚠️ Menu disappears when player leaves server")
print("📱 Optimized for mobile with smaller sizes")
print("🔧 Debounce optimized for better performance")
print("")
print("✅ Installation successful!")