-- DroneCam Debug System untuk Roblox
-- Script untuk debug dan troubleshoot DroneCam system
-- Membantu mengidentifikasi masalah chat commands

local RunService = game:GetService("RunService")
if not RunService:IsClient() then return end

local Players = game:GetService("Players")
local UserInputService = game:GetService("UserInputService")
local StarterGui = game:GetService("StarterGui")

local LOCAL_PLAYER = Players.LocalPlayer
local playerGui = LOCAL_PLAYER:WaitForChild("PlayerGui")

-- Cleanup
do
	local old = playerGui:FindFirstChild("DroneCamDebugSystem")
	if old then old:Destroy() end
end

-- Debug variables
local debugActive = false
local chatLogs = {}
local maxLogs = 50

-- ScreenGui
local gui = Instance.new("ScreenGui")
gui.Name = "DroneCamDebugSystem"
gui.IgnoreGuiInset = true
gui.ResetOnSpawn = false
gui.DisplayOrder = 2000
gui.Parent = playerGui

-- Debug Panel
local debugPanel = Instance.new("Frame")
debugPanel.Name = "DebugPanel"
debugPanel.Size = UDim2.new(0, 400, 0, 300)
debugPanel.Position = UDim2.new(0, 10, 0, 10)
debugPanel.BackgroundColor3 = Color3.fromRGB(0, 0, 0)
debugPanel.BackgroundTransparency = 0.2
debugPanel.BorderSizePixel = 0
debugPanel.Visible = false
debugPanel.ZIndex = 20
debugPanel.Parent = gui

local corner = Instance.new("UICorner")
corner.CornerRadius = UDim.new(0, 8)
corner.Parent = debugPanel

local stroke = Instance.new("UIStroke")
stroke.Color = Color3.fromRGB(255, 255, 255)
stroke.Thickness = 1
stroke.Parent = debugPanel

local padding = Instance.new("UIPadding")
padding.PaddingLeft = UDim.new(0, 10)
padding.PaddingRight = UDim.new(0, 10)
padding.PaddingTop = UDim.new(0, 10)
padding.PaddingBottom = UDim.new(0, 10)
padding.Parent = debugPanel

local layout = Instance.new("UIListLayout")
layout.FillDirection = Enum.FillDirection.Vertical
layout.VerticalAlignment = Enum.VerticalAlignment.Top
layout.HorizontalAlignment = Enum.HorizontalAlignment.Left
layout.Padding = UDim.new(0, 5)
layout.Parent = debugPanel

-- Title
local title = Instance.new("TextLabel")
title.Size = UDim2.new(1, 0, 0, 25)
title.BackgroundTransparency = 1
title.Text = "🔧 DRONE CAM DEBUG"
title.TextColor3 = Color3.fromRGB(255, 255, 255)
title.TextScaled = true
title.Font = Enum.Font.GothamBold
title.TextStrokeTransparency = 0.5
title.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
title.Parent = debugPanel

-- Status
local status = Instance.new("TextLabel")
status.Size = UDim2.new(1, 0, 0, 20)
status.BackgroundTransparency = 1
status.Text = "Status: Ready"
status.TextColor3 = Color3.fromRGB(0, 255, 0)
status.TextScaled = true
status.Font = Enum.Font.Gotham
status.TextStrokeTransparency = 0.5
status.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
status.Parent = debugPanel

-- Chat Log
local chatLog = Instance.new("TextLabel")
chatLog.Size = UDim2.new(1, 0, 1, -50)
chatLog.BackgroundTransparency = 1
chatLog.Text = "Chat Log:\nWaiting for messages..."
chatLog.TextColor3 = Color3.fromRGB(255, 255, 255)
chatLog.TextScaled = true
chatLog.Font = Enum.Font.Gotham
chatLog.TextStrokeTransparency = 0.5
chatLog.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
chatLog.TextWrapped = true
chatLog.TextXAlignment = Enum.TextXAlignment.Left
chatLog.TextYAlignment = Enum.TextYAlignment.Top
chatLog.Parent = debugPanel

-- Toggle Button
local toggleBtn = Instance.new("TextButton")
toggleBtn.Size = UDim2.new(0, 100, 0, 30)
toggleBtn.Position = UDim2.new(1, -110, 0, 10)
toggleBtn.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
toggleBtn.BorderSizePixel = 0
toggleBtn.Text = "Debug ON"
toggleBtn.TextColor3 = Color3.fromRGB(255, 255, 255)
toggleBtn.TextScaled = true
toggleBtn.Font = Enum.Font.GothamBold
toggleBtn.ZIndex = 21
toggleBtn.Parent = gui

local btnCorner = Instance.new("UICorner")
btnCorner.CornerRadius = UDim.new(0, 6)
btnCorner.Parent = toggleBtn

local btnStroke = Instance.new("UIStroke")
btnStroke.Color = Color3.fromRGB(255, 255, 255)
btnStroke.Thickness = 1
btnStroke.Parent = toggleBtn

-- Functions
local function addChatLog(message)
	local timestamp = os.date("%H:%M:%S")
	local logEntry = string.format("[%s] %s", timestamp, message)
	
	table.insert(chatLogs, logEntry)
	
	if #chatLogs > maxLogs then
		table.remove(chatLogs, 1)
	end
	
	local logText = "Chat Log:\n" .. table.concat(chatLogs, "\n")
	chatLog.Text = logText
end

local function updateStatus(message, color)
	status.Text = "Status: " .. message
	status.TextColor3 = color or Color3.fromRGB(255, 255, 255)
end

local function toggleDebug()
	debugActive = not debugActive
	debugPanel.Visible = debugActive
	
	if debugActive then
		toggleBtn.Text = "Debug OFF"
		toggleBtn.BackgroundColor3 = Color3.fromRGB(255, 0, 0)
		updateStatus("Debug Active", Color3.fromRGB(0, 255, 0))
		addChatLog("Debug system activated")
	else
		toggleBtn.Text = "Debug ON"
		toggleBtn.BackgroundColor3 = Color3.fromRGB(0, 150, 0)
		updateStatus("Debug Inactive", Color3.fromRGB(255, 255, 0))
	end
end

-- Chat handling
local function onChatted(player, message)
	if player ~= LOCAL_PLAYER then return end
	
	local lowerMessage = message:lower()
	addChatLog("Chat: " .. lowerMessage)
	
	-- Check for drone commands
	if lowerMessage == "/drone" then
		addChatLog("✅ DRONE COMMAND DETECTED!")
		updateStatus("Drone command received", Color3.fromRGB(0, 255, 0))
	elseif lowerMessage == "/offdrone" then
		addChatLog("✅ OFF DRONE COMMAND DETECTED!")
		updateStatus("Off drone command received", Color3.fromRGB(255, 0, 0))
	elseif lowerMessage == "/debug" then
		addChatLog("✅ DEBUG COMMAND DETECTED!")
		toggleDebug()
	elseif lowerMessage == "/test" then
		addChatLog("✅ TEST COMMAND DETECTED!")
		updateStatus("Test command received", Color3.fromRGB(255, 165, 0))
	end
end

-- Test functions
local function testChatSystem()
	addChatLog("🧪 Testing chat system...")
	
	-- Test if chat connection works
	local testMessage = "Test message from debug system"
	onChatted(LOCAL_PLAYER, testMessage)
	
	-- Test drone commands
	onChatted(LOCAL_PLAYER, "/drone")
	onChatted(LOCAL_PLAYER, "/offdrone")
	
	updateStatus("Chat system tested", Color3.fromRGB(0, 255, 255))
end

local function testPlayerInfo()
	addChatLog("🧪 Testing player info...")
	addChatLog("Player: " .. LOCAL_PLAYER.Name)
	addChatLog("UserID: " .. LOCAL_PLAYER.UserId)
	addChatLog("PlayerGui: " .. tostring(playerGui))
	addChatLog("Character: " .. tostring(LOCAL_PLAYER.Character))
	
	updateStatus("Player info tested", Color3.fromRGB(0, 255, 255))
end

local function testServices()
	addChatLog("🧪 Testing services...")
	addChatLog("RunService: " .. tostring(RunService))
	addChatLog("UserInputService: " .. tostring(UserInputService))
	addChatLog("StarterGui: " .. tostring(StarterGui))
	addChatLog("Players: " .. tostring(Players))
	
	updateStatus("Services tested", Color3.fromRGB(0, 255, 255))
end

-- Connect events
LOCAL_PLAYER.Chatted:Connect(onChatted)

toggleBtn.MouseButton1Click:Connect(toggleDebug)

-- Global functions
_G.DroneCamDebugSystem = {
	-- Toggle debug
	toggle = toggleDebug,
	
	-- Test functions
	testChat = testChatSystem,
	testPlayer = testPlayerInfo,
	testServices = testServices,
	
	-- Add log
	addLog = addChatLog,
	
	-- Update status
	updateStatus = updateStatus,
	
	-- Get logs
	getLogs = function()
		return chatLogs
	end,
	
	-- Clear logs
	clearLogs = function()
		chatLogs = {}
		chatLog.Text = "Chat Log:\nLogs cleared"
	end,
	
	-- Check if debug is active
	isActive = function()
		return debugActive
	end
}

-- Commands
print("🔧 COMMANDS UNTUK TEST DRONE CAM DEBUG:")
print("_G.DroneCamDebugSystem.toggle() - Toggle debug panel")
print("_G.DroneCamDebugSystem.testChat() - Test chat system")
print("_G.DroneCamDebugSystem.testPlayer() - Test player info")
print("_G.DroneCamDebugSystem.testServices() - Test services")
print("_G.DroneCamDebugSystem.addLog('message') - Add log message")
print("_G.DroneCamDebugSystem.updateStatus('message') - Update status")
print("_G.DroneCamDebugSystem.getLogs() - Get chat logs")
print("_G.DroneCamDebugSystem.clearLogs() - Clear logs")
print("_G.DroneCamDebugSystem.isActive() - Check if debug is active")
print("")
print("💬 DEBUG CHAT COMMANDS:")
print("/debug - Toggle debug panel")
print("/test - Test command")
print("/drone - Test drone command")
print("/offdrone - Test off drone command")
print("")
print("🎮 CONTROLS:")
print("Click 'Debug ON' button to toggle debug panel")
print("Debug panel shows all chat messages")
print("Status updates in real-time")
print("")
print("✅ DEBUG FEATURES:")
print("- Real-time chat monitoring")
print("- Command detection testing")
print("- Player info testing")
print("- Services testing")
print("- Log management")
print("- Status updates")
print("- Easy troubleshooting")
print("")
print("🔧 DRONE CAM DEBUG SYSTEM READY!")
print("Click 'Debug ON' button to start debugging!")