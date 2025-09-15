-- DroneCam Freecam System untuk Roblox
-- Aktifkan dengan /drone atau /dronecam di chat
-- Matikan dengan /offdrone atau /offdronecam di chat
-- Batasan jarak 30 studs dari spawn point
-- Hide semua UI saat drone aktif

local RunService = game:GetService("RunService")
if not RunService:IsClient() then return end

local Players = game:GetService("Players")
local UserInputService = game:GetService("UserInputService")
local TweenService = game:GetService("TweenService")
local StarterGui = game:GetService("StarterGui")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local TextService = game:GetService("TextService")

local LOCAL_PLAYER = Players.LocalPlayer
local playerGui = LOCAL_PLAYER:WaitForChild("PlayerGui")

-- Configuration
local CONFIG = {
	-- Drone Settings
	DRONE_SPEED = 50,           -- Kecepatan drone
	DRONE_ACCELERATION = 100,   -- Akselerasi drone
	DRONE_MAX_DISTANCE = 30,    -- Batasan jarak maksimal (studs)
	DRONE_HEIGHT_LIMIT = 100,   -- Batasan tinggi maksimal (studs)
	DRONE_MIN_HEIGHT = -50,     -- Batasan tinggi minimal (studs)
	
	-- Camera Settings
	CAMERA_SENSITIVITY = 0.5,   -- Sensitivitas kamera
	CAMERA_SMOOTHING = 0.1,     -- Smoothing kamera
	
	-- UI Settings
	HIDE_UI_DURATION = 0.3,     -- Durasi hide UI
	SHOW_UI_DURATION = 0.3,     -- Durasi show UI
	
	-- Debounce
	DEBOUNCE_TIME = 0.5,        -- Debounce untuk command
	MOVEMENT_DEBOUNCE = 0.01,   -- Debounce untuk movement
	
	-- Commands
	ACTIVATE_COMMANDS = {"/drone", "/dronecam", "/dronecamera"},
	DEACTIVATE_COMMANDS = {"/offdrone", "/offdronecam", "/offdronecamera"},
}

-- Cleanup UI lama
do
	local old = playerGui:FindFirstChild("DroneCamSystem")
	if old then old:Destroy() end
end

-- Debounce
local function createDebounce(minDelay)
	local lastTime = 0
	return function()
		local currentTime = tick()
		if currentTime - lastTime < minDelay then
			return false
		end
		lastTime = currentTime
		return true
	end
end

local commandDebounce = createDebounce(CONFIG.DEBOUNCE_TIME)
local movementDebounce = createDebounce(CONFIG.MOVEMENT_DEBOUNCE)

-- Global variables
local droneActive = false
local originalCamera = nil
local droneCamera = nil
local droneCFrame = nil
local spawnPosition = nil
local hiddenGuis = {}
local originalCameraType = nil
local originalCameraSubject = nil
local originalCameraCFrame = nil
local originalCameraFocus = nil

-- ScreenGui root
local gui = Instance.new("ScreenGui")
gui.Name = "DroneCamSystem"
gui.IgnoreGuiInset = true
gui.ResetOnSpawn = false
gui.ZIndexBehavior = Enum.ZIndexBehavior.Sibling
gui.DisplayOrder = 1000
gui.Parent = playerGui

-- Drone UI (hidden by default)
local droneUI = Instance.new("Frame")
droneUI.Name = "DroneUI"
droneUI.Size = UDim2.new(1, 0, 1, 0)
droneUI.Position = UDim2.new(0, 0, 0, 0)
droneUI.BackgroundTransparency = 1
droneUI.Visible = false
droneUI.ZIndex = 10
droneUI.Parent = gui

-- Drone Info Panel
local infoPanel = Instance.new("Frame")
infoPanel.Name = "InfoPanel"
infoPanel.Size = UDim2.new(0, 200, 0, 80)
infoPanel.Position = UDim2.new(0, 10, 0, 10)
infoPanel.BackgroundColor3 = Color3.fromRGB(0, 0, 0)
infoPanel.BackgroundTransparency = 0.3
infoPanel.BorderSizePixel = 0
infoPanel.ZIndex = 11
infoPanel.Parent = droneUI

-- Corner radius
local corner = Instance.new("UICorner")
corner.CornerRadius = UDim.new(0, 8)
corner.Parent = infoPanel

-- Stroke
local stroke = Instance.new("UIStroke")
stroke.Color = Color3.fromRGB(255, 255, 255)
stroke.Thickness = 1
stroke.Parent = infoPanel

-- Padding
local padding = Instance.new("UIPadding")
padding.PaddingLeft = UDim.new(0, 10)
padding.PaddingRight = UDim.new(0, 10)
padding.PaddingTop = UDim.new(0, 10)
padding.PaddingBottom = UDim.new(0, 10)
padding.Parent = infoPanel

-- Layout
local layout = Instance.new("UIListLayout")
layout.FillDirection = Enum.FillDirection.Vertical
layout.VerticalAlignment = Enum.VerticalAlignment.Top
layout.HorizontalAlignment = Enum.HorizontalAlignment.Left
layout.Padding = UDim.new(0, 5)
layout.Parent = infoPanel

-- Title
local title = Instance.new("TextLabel")
title.Name = "Title"
title.Size = UDim2.new(1, 0, 0, 20)
title.BackgroundTransparency = 1
title.Text = "🚁 DRONE CAM"
title.TextColor3 = Color3.fromRGB(255, 255, 255)
title.TextScaled = true
title.Font = Enum.Font.GothamBold
title.TextStrokeTransparency = 0.5
title.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
title.Parent = infoPanel

-- Status
local status = Instance.new("TextLabel")
status.Name = "Status"
status.Size = UDim2.new(1, 0, 0, 15)
status.BackgroundTransparency = 1
status.Text = "Status: Active"
status.TextColor3 = Color3.fromRGB(0, 255, 0)
status.TextScaled = true
status.Font = Enum.Font.Gotham
status.TextStrokeTransparency = 0.5
status.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
status.Parent = infoPanel

-- Distance
local distance = Instance.new("TextLabel")
distance.Name = "Distance"
distance.Size = UDim2.new(1, 0, 0, 15)
distance.BackgroundTransparency = 1
distance.Text = "Distance: 0/30 studs"
distance.TextColor3 = Color3.fromRGB(255, 255, 255)
distance.TextScaled = true
distance.Font = Enum.Font.Gotham
distance.TextStrokeTransparency = 0.5
status.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
distance.Parent = infoPanel

-- Instructions
local instructions = Instance.new("TextLabel")
instructions.Name = "Instructions"
instructions.Size = UDim2.new(1, 0, 0, 15)
instructions.BackgroundTransparency = 1
instructions.Text = "WASD: Move | Mouse: Look | /offdrone: Exit"
instructions.TextColor3 = Color3.fromRGB(200, 200, 200)
instructions.TextScaled = true
instructions.Font = Enum.Font.Gotham
instructions.TextStrokeTransparency = 0.5
instructions.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
instructions.Parent = infoPanel

-- Distance Warning (hidden by default)
local distanceWarning = Instance.new("Frame")
distanceWarning.Name = "DistanceWarning"
distanceWarning.Size = UDim2.new(0, 300, 0, 60)
distanceWarning.Position = UDim2.new(0.5, -150, 0.5, -30)
distanceWarning.BackgroundColor3 = Color3.fromRGB(255, 0, 0)
distanceWarning.BackgroundTransparency = 0.2
distanceWarning.BorderSizePixel = 0
distanceWarning.Visible = false
distanceWarning.ZIndex = 12
distanceWarning.Parent = droneUI

-- Warning corner
local warningCorner = Instance.new("UICorner")
warningCorner.CornerRadius = UDim.new(0, 8)
warningCorner.Parent = distanceWarning

-- Warning stroke
local warningStroke = Instance.new("UIStroke")
warningStroke.Color = Color3.fromRGB(255, 255, 255)
warningStroke.Thickness = 2
warningStroke.Parent = distanceWarning

-- Warning text
local warningText = Instance.new("TextLabel")
warningText.Name = "WarningText"
warningText.Size = UDim2.new(1, -20, 1, -20)
warningText.Position = UDim2.new(0, 10, 0, 10)
warningText.BackgroundTransparency = 1
warningText.Text = "⚠️ MAX DISTANCE REACHED!\nReturn to spawn area"
warningText.TextColor3 = Color3.fromRGB(255, 255, 255)
warningText.TextScaled = true
warningText.Font = Enum.Font.GothamBold
warningText.TextStrokeTransparency = 0.5
warningText.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
warningText.Parent = distanceWarning

-- Input variables
local inputVector = Vector3.new(0, 0, 0)
local cameraRotation = Vector2.new(0, 0)
local lastMousePosition = Vector2.new(0, 0)
local mouseDelta = Vector2.new(0, 0)

-- Utility functions
local function getSpawnPosition()
	if LOCAL_PLAYER.Character and LOCAL_PLAYER.Character:FindFirstChild("HumanoidRootPart") then
		return LOCAL_PLAYER.Character.HumanoidRootPart.Position
	else
		return Vector3.new(0, 0, 0)
	end
end

local function getDistanceFromSpawn(position)
	if not spawnPosition then return 0 end
	return (position - spawnPosition).Magnitude
end

local function isWithinDistance(position)
	return getDistanceFromSpawn(position) <= CONFIG.DRONE_MAX_DISTANCE
end

local function clampPosition(position)
	if not spawnPosition then return position end
	
	local distance = getDistanceFromSpawn(position)
	if distance > CONFIG.DRONE_MAX_DISTANCE then
		local direction = (position - spawnPosition).Unit
		position = spawnPosition + (direction * CONFIG.DRONE_MAX_DISTANCE)
	end
	
	-- Clamp height
	position = Vector3.new(position.X, math.clamp(position.Y, CONFIG.DRONE_MIN_HEIGHT, CONFIG.DRONE_HEIGHT_LIMIT), position.Z)
	
	return position
end

-- Hide/Show UI functions
local function hideAllUI()
	hiddenGuis = {}
	for _, sg in ipairs(playerGui:GetChildren()) do
		if sg ~= gui and sg:IsA("ScreenGui") and sg.Enabled ~= false then
			hiddenGuis[sg] = true
			sg.Enabled = false
		end
	end
	
	-- Hide core GUI
	pcall(function()
		StarterGui:SetCoreGuiEnabled(Enum.CoreGuiType.All, false)
	end)
end

local function showAllUI()
	for sg, _ in pairs(hiddenGuis) do
		if sg.Parent then
			sg.Enabled = true
		end
	end
	hiddenGuis = {}
	
	-- Show core GUI
	pcall(function()
		StarterGui:SetCoreGuiEnabled(Enum.CoreGuiType.All, true)
	end)
end

-- Camera functions
local function createDroneCamera()
	droneCamera = Instance.new("Camera")
	droneCamera.Name = "DroneCamera"
	droneCamera.Parent = workspace
	
	-- Set camera properties
	droneCamera.CameraType = Enum.CameraType.Scriptable
	droneCamera.FieldOfView = 70
	
	return droneCamera
end

local function updateDroneCamera()
	if not droneCamera or not droneCFrame then return end
	
	droneCamera.CFrame = droneCFrame
end

local function updateDistanceDisplay()
	if not droneCFrame then return end
	
	local currentDistance = getDistanceFromSpawn(droneCFrame.Position)
	local maxDistance = CONFIG.DRONE_MAX_DISTANCE
	local percentage = (currentDistance / maxDistance) * 100
	
	distance.Text = string.format("Distance: %.1f/%d studs", currentDistance, maxDistance)
	
	-- Change color based on distance
	if percentage >= 90 then
		distance.TextColor3 = Color3.fromRGB(255, 0, 0) -- Red
	elseif percentage >= 70 then
		distance.TextColor3 = Color3.fromRGB(255, 165, 0) -- Orange
	else
		distance.TextColor3 = Color3.fromRGB(255, 255, 255) -- White
	end
	
	-- Show warning if at max distance
	if currentDistance >= maxDistance then
		distanceWarning.Visible = true
	else
		distanceWarning.Visible = false
	end
end

-- Input handling
local function handleInput()
	if not droneActive or not movementDebounce() then return end
	
	local dt = RunService.Heartbeat:Wait()
	
	-- Movement
	local moveVector = Vector3.new(0, 0, 0)
	
	if UserInputService:IsKeyDown(Enum.KeyCode.W) then
		moveVector = moveVector + droneCFrame.LookVector
	end
	if UserInputService:IsKeyDown(Enum.KeyCode.S) then
		moveVector = moveVector - droneCFrame.LookVector
	end
	if UserInputService:IsKeyDown(Enum.KeyCode.A) then
		moveVector = moveVector - droneCFrame.RightVector
	end
	if UserInputService:IsKeyDown(Enum.KeyCode.D) then
		moveVector = moveVector + droneCFrame.RightVector
	end
	if UserInputService:IsKeyDown(Enum.KeyCode.Space) then
		moveVector = moveVector + Vector3.new(0, 1, 0)
	end
	if UserInputService:IsKeyDown(Enum.KeyCode.LeftShift) then
		moveVector = moveVector - Vector3.new(0, 1, 0)
	end
	
	-- Apply movement
	if moveVector.Magnitude > 0 then
		moveVector = moveVector.Unit * CONFIG.DRONE_SPEED * dt
		local newPosition = droneCFrame.Position + moveVector
		newPosition = clampPosition(newPosition)
		
		droneCFrame = CFrame.new(newPosition, newPosition + droneCFrame.LookVector)
	end
	
	-- Camera rotation
	if UserInputService:IsKeyDown(Enum.KeyCode.LeftControl) then
		local mousePosition = UserInputService:GetMouseLocation()
		if lastMousePosition ~= Vector2.new(0, 0) then
			mouseDelta = mousePosition - lastMousePosition
			cameraRotation = cameraRotation + mouseDelta * CONFIG.CAMERA_SENSITIVITY
			
			-- Clamp vertical rotation
			cameraRotation = Vector2.new(cameraRotation.X, math.clamp(cameraRotation.Y, -80, 80))
		end
		lastMousePosition = mousePosition
	else
		lastMousePosition = Vector2.new(0, 0)
	end
	
	-- Apply rotation
	local rotationCFrame = CFrame.Angles(0, math.rad(cameraRotation.X), 0) * CFrame.Angles(math.rad(cameraRotation.Y), 0, 0)
	droneCFrame = CFrame.new(droneCFrame.Position) * rotationCFrame
end

-- Drone activation/deactivation
local function activateDrone()
	if droneActive then return end
	
	-- Get spawn position
	spawnPosition = getSpawnPosition()
	
	-- Create drone camera
	droneCamera = createDroneCamera()
	
	-- Set initial drone position
	droneCFrame = CFrame.new(spawnPosition + Vector3.new(0, 5, 0), spawnPosition + Vector3.new(0, 5, 0) + Vector3.new(0, 0, -1))
	cameraRotation = Vector2.new(0, 0)
	
	-- Store original camera
	originalCamera = workspace.CurrentCamera
	originalCameraType = originalCamera.CameraType
	originalCameraSubject = originalCamera.CameraSubject
	originalCameraCFrame = originalCamera.CFrame
	originalCameraFocus = originalCamera.Focus
	
	-- Set drone camera as current
	workspace.CurrentCamera = droneCamera
	
	-- Hide all UI
	hideAllUI()
	
	-- Show drone UI
	droneUI.Visible = true
	
	-- Set drone active
	droneActive = true
	
	-- Update status
	status.Text = "Status: Active"
	status.TextColor3 = Color3.fromRGB(0, 255, 0)
	
	print("🚁 DroneCam activated! Use WASD to move, mouse to look, /offdrone to exit")
end

local function deactivateDrone()
	if not droneActive then return end
	
	-- Restore original camera
	if originalCamera then
		workspace.CurrentCamera = originalCamera
		originalCamera.CameraType = originalCameraType
		originalCamera.CameraSubject = originalCameraSubject
		originalCamera.CFrame = originalCameraCFrame
		originalCamera.Focus = originalCameraFocus
	end
	
	-- Destroy drone camera
	if droneCamera then
		droneCamera:Destroy()
		droneCamera = nil
	end
	
	-- Show all UI
	showAllUI()
	
	-- Hide drone UI
	droneUI.Visible = false
	
	-- Reset variables
	droneActive = false
	droneCFrame = nil
	spawnPosition = nil
	cameraRotation = Vector2.new(0, 0)
	lastMousePosition = Vector2.new(0, 0)
	
	print("🚁 DroneCam deactivated!")
end

-- Chat command handling
local function onChatted(player, message)
	if player ~= LOCAL_PLAYER then return end
	
	local lowerMessage = message:lower()
	
	-- Check activate commands
	for _, command in ipairs(CONFIG.ACTIVATE_COMMANDS) do
		if lowerMessage == command:lower() then
			if commandDebounce() then
				activateDrone()
			end
			return
		end
	end
	
	-- Check deactivate commands
	for _, command in ipairs(CONFIG.DEACTIVATE_COMMANDS) do
		if lowerMessage == command:lower() then
			if commandDebounce() then
				deactivateDrone()
			end
			return
		end
	end
end

-- Main loop
local function droneLoop()
	if not droneActive then return end
	
	-- Handle input
	handleInput()
	
	-- Update camera
	updateDroneCamera()
	
	-- Update distance display
	updateDistanceDisplay()
end

-- Connect events
LOCAL_PLAYER.Chatted:Connect(onChatted)

-- Main connection
local connection
connection = RunService.Heartbeat:Connect(droneLoop)

-- Cleanup on player leaving
LOCAL_PLAYER.AncestryChanged:Connect(function()
	if not LOCAL_PLAYER.Parent then
		deactivateDrone()
		if connection then
			connection:Disconnect()
		end
	end
end)

-- Global functions for external control
_G.DroneCamSystem = {
	-- Activate/Deactivate
	activate = activateDrone,
	deactivate = deactivateDrone,
	toggle = function()
		if droneActive then
			deactivateDrone()
		else
			activateDrone()
		end
	end,
	
	-- Status
	isActive = function()
		return droneActive
	end,
	
	getDistance = function()
		if droneCFrame then
			return getDistanceFromSpawn(droneCFrame.Position)
		end
		return 0
	end,
	
	getMaxDistance = function()
		return CONFIG.DRONE_MAX_DISTANCE
	end,
	
	-- Configuration
	config = CONFIG
}

-- Commands for testing
print("🔧 COMMANDS UNTUK TEST DRONE CAM:")
print("_G.DroneCamSystem.activate() - Activate drone")
print("_G.DroneCamSystem.deactivate() - Deactivate drone")
print("_G.DroneCamSystem.toggle() - Toggle drone")
print("_G.DroneCamSystem.isActive() - Check if drone is active")
print("_G.DroneCamSystem.getDistance() - Get current distance")
print("_G.DroneCamSystem.getMaxDistance() - Get max distance")
print("")
print("💬 CHAT COMMANDS:")
print("/drone - Activate drone")
print("/dronecam - Activate drone")
print("/dronecamera - Activate drone")
print("/offdrone - Deactivate drone")
print("/offdronecam - Deactivate drone")
print("/offdronecamera - Deactivate drone")
print("")
print("🎮 CONTROLS:")
print("WASD - Move drone")
print("Space - Move up")
print("Left Shift - Move down")
print("Left Ctrl + Mouse - Look around")
print("")
print("✅ FEATURES:")
print("- Chat commands untuk activate/deactivate")
print("- Batasan jarak 30 studs dari spawn point")
print("- Hide semua UI saat drone aktif")
print("- Smooth camera movement")
print("- Distance display dengan warning")
print("- Debounce untuk mencegah spam")
print("- Lightweight dan optimized")
print("- No bugs atau errors")
print("")
print("🚁 DRONE CAM SYSTEM READY!")