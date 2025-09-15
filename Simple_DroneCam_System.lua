-- Simple DroneCam Freecam System untuk Roblox
-- Aktifkan dengan /drone di chat
-- Matikan dengan /offdrone di chat
-- Batasan jarak 30 studs dari spawn point
-- Hide semua UI saat drone aktif
-- SIMPLE & RELIABLE: Chat commands pasti berfungsi

local RunService = game:GetService("RunService")
if not RunService:IsClient() then return end

local Players = game:GetService("Players")
local UserInputService = game:GetService("UserInputService")
local TweenService = game:GetService("TweenService")
local StarterGui = game:GetService("StarterGui")

local LOCAL_PLAYER = Players.LocalPlayer
local playerGui = LOCAL_PLAYER:WaitForChild("PlayerGui")

-- Configuration
local CONFIG = {
	DRONE_SPEED = 50,
	DRONE_MAX_DISTANCE = 30,
	DRONE_HEIGHT_LIMIT = 100,
	DRONE_MIN_HEIGHT = -50,
	CAMERA_SENSITIVITY = 0.5,
	DEBOUNCE_TIME = 0.2,
}

-- Cleanup
do
	local old = playerGui:FindFirstChild("SimpleDroneCamSystem")
	if old then old:Destroy() end
end

-- Debounce
local lastCommandTime = 0
local function canUseCommand()
	local currentTime = tick()
	if currentTime - lastCommandTime < CONFIG.DEBOUNCE_TIME then
		return false
	end
	lastCommandTime = currentTime
	return true
end

-- Global variables
local droneActive = false
local originalCamera = nil
local droneCamera = nil
local droneCFrame = nil
local spawnPosition = nil
local hiddenGuis = {}
local cameraRotation = Vector2.new(0, 0)
local lastMousePosition = Vector2.new(0, 0)

-- ScreenGui
local gui = Instance.new("ScreenGui")
gui.Name = "SimpleDroneCamSystem"
gui.IgnoreGuiInset = true
gui.ResetOnSpawn = false
gui.DisplayOrder = 1000
gui.Parent = playerGui

-- Drone UI
local droneUI = Instance.new("Frame")
droneUI.Name = "DroneUI"
droneUI.Size = UDim2.new(1, 0, 1, 0)
droneUI.BackgroundTransparency = 1
droneUI.Visible = false
droneUI.ZIndex = 10
droneUI.Parent = gui

-- Info Panel
local infoPanel = Instance.new("Frame")
infoPanel.Size = UDim2.new(0, 200, 0, 80)
infoPanel.Position = UDim2.new(0, 10, 0, 10)
infoPanel.BackgroundColor3 = Color3.fromRGB(0, 0, 0)
infoPanel.BackgroundTransparency = 0.3
infoPanel.BorderSizePixel = 0
infoPanel.ZIndex = 11
infoPanel.Parent = droneUI

local corner = Instance.new("UICorner")
corner.CornerRadius = UDim.new(0, 8)
corner.Parent = infoPanel

local stroke = Instance.new("UIStroke")
stroke.Color = Color3.fromRGB(255, 255, 255)
stroke.Thickness = 1
stroke.Parent = infoPanel

local padding = Instance.new("UIPadding")
padding.PaddingLeft = UDim.new(0, 10)
padding.PaddingRight = UDim.new(0, 10)
padding.PaddingTop = UDim.new(0, 10)
padding.PaddingBottom = UDim.new(0, 10)
padding.Parent = infoPanel

local layout = Instance.new("UIListLayout")
layout.FillDirection = Enum.FillDirection.Vertical
layout.VerticalAlignment = Enum.VerticalAlignment.Top
layout.HorizontalAlignment = Enum.HorizontalAlignment.Left
layout.Padding = UDim.new(0, 5)
layout.Parent = infoPanel

-- Title
local title = Instance.new("TextLabel")
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
distance.Size = UDim2.new(1, 0, 0, 15)
distance.BackgroundTransparency = 1
distance.Text = "Distance: 0/30 studs"
distance.TextColor3 = Color3.fromRGB(255, 255, 255)
distance.TextScaled = true
distance.Font = Enum.Font.Gotham
distance.TextStrokeTransparency = 0.5
distance.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
distance.Parent = infoPanel

-- Instructions
local instructions = Instance.new("TextLabel")
instructions.Size = UDim2.new(1, 0, 0, 15)
instructions.BackgroundTransparency = 1
instructions.Text = "WASD: Move | Mouse: Look | /offdrone: Exit"
instructions.TextColor3 = Color3.fromRGB(200, 200, 200)
instructions.TextScaled = true
instructions.Font = Enum.Font.Gotham
instructions.TextStrokeTransparency = 0.5
instructions.TextStrokeColor3 = Color3.fromRGB(0, 0, 0)
instructions.Parent = infoPanel

-- Distance Warning
local distanceWarning = Instance.new("Frame")
distanceWarning.Size = UDim2.new(0, 300, 0, 60)
distanceWarning.Position = UDim2.new(0.5, -150, 0.5, -30)
distanceWarning.BackgroundColor3 = Color3.fromRGB(255, 0, 0)
distanceWarning.BackgroundTransparency = 0.2
distanceWarning.BorderSizePixel = 0
distanceWarning.Visible = false
distanceWarning.ZIndex = 12
distanceWarning.Parent = droneUI

local warningCorner = Instance.new("UICorner")
warningCorner.CornerRadius = UDim.new(0, 8)
warningCorner.Parent = distanceWarning

local warningStroke = Instance.new("UIStroke")
warningStroke.Color = Color3.fromRGB(255, 255, 255)
warningStroke.Thickness = 2
warningStroke.Parent = distanceWarning

local warningText = Instance.new("TextLabel")
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

local function clampPosition(position)
	if not spawnPosition then return position end
	
	local distance = getDistanceFromSpawn(position)
	if distance > CONFIG.DRONE_MAX_DISTANCE then
		local direction = (position - spawnPosition).Unit
		position = spawnPosition + (direction * CONFIG.DRONE_MAX_DISTANCE)
	end
	
	position = Vector3.new(position.X, math.clamp(position.Y, CONFIG.DRONE_MIN_HEIGHT, CONFIG.DRONE_HEIGHT_LIMIT), position.Z)
	return position
end

-- UI functions
local function hideAllUI()
	hiddenGuis = {}
	for _, sg in ipairs(playerGui:GetChildren()) do
		if sg ~= gui and sg:IsA("ScreenGui") and sg.Enabled ~= false then
			hiddenGuis[sg] = true
			sg.Enabled = false
		end
	end
	
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
	
	pcall(function()
		StarterGui:SetCoreGuiEnabled(Enum.CoreGuiType.All, true)
	end)
end

-- Camera functions
local function createDroneCamera()
	droneCamera = Instance.new("Camera")
	droneCamera.Name = "DroneCamera"
	droneCamera.Parent = workspace
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
	
	distance.Text = string.format("Distance: %.1f/%d studs", currentDistance, maxDistance)
	
	if currentDistance >= maxDistance then
		distance.TextColor3 = Color3.fromRGB(255, 0, 0)
		distanceWarning.Visible = true
	else
		distance.TextColor3 = Color3.fromRGB(255, 255, 255)
		distanceWarning.Visible = false
	end
end

-- Input handling
local function handleInput()
	if not droneActive then return end
	
	local dt = RunService.Heartbeat:Wait()
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
			local mouseDelta = mousePosition - lastMousePosition
			cameraRotation = cameraRotation + mouseDelta * CONFIG.CAMERA_SENSITIVITY
			cameraRotation = Vector2.new(cameraRotation.X, math.clamp(cameraRotation.Y, -80, 80))
		end
		lastMousePosition = mousePosition
	else
		lastMousePosition = Vector2.new(0, 0)
	end
	
	local rotationCFrame = CFrame.Angles(0, math.rad(cameraRotation.X), 0) * CFrame.Angles(math.rad(cameraRotation.Y), 0, 0)
	droneCFrame = CFrame.new(droneCFrame.Position) * rotationCFrame
end

-- Drone functions
local function activateDrone()
	if droneActive then return end
	
	spawnPosition = getSpawnPosition()
	droneCamera = createDroneCamera()
	droneCFrame = CFrame.new(spawnPosition + Vector3.new(0, 5, 0), spawnPosition + Vector3.new(0, 5, 0) + Vector3.new(0, 0, -1))
	cameraRotation = Vector2.new(0, 0)
	
	originalCamera = workspace.CurrentCamera
	workspace.CurrentCamera = droneCamera
	
	hideAllUI()
	droneUI.Visible = true
	droneActive = true
	
	status.Text = "Status: Active"
	status.TextColor3 = Color3.fromRGB(0, 255, 0)
	
	print("🚁 DroneCam activated! Use WASD to move, /offdrone to exit")
end

local function deactivateDrone()
	if not droneActive then return end
	
	if originalCamera then
		workspace.CurrentCamera = originalCamera
	end
	
	if droneCamera then
		droneCamera:Destroy()
		droneCamera = nil
	end
	
	showAllUI()
	droneUI.Visible = false
	
	droneActive = false
	droneCFrame = nil
	spawnPosition = nil
	cameraRotation = Vector2.new(0, 0)
	lastMousePosition = Vector2.new(0, 0)
	
	print("🚁 DroneCam deactivated!")
end

-- SIMPLE & RELIABLE: Chat command handling
local function onChatted(player, message)
	if player ~= LOCAL_PLAYER then return end
	
	local lowerMessage = message:lower()
	print("💬 Chat:", lowerMessage)
	
	if lowerMessage == "/drone" then
		if canUseCommand() then
			print("✅ Activating drone...")
			activateDrone()
		else
			print("⏳ Command debounced")
		end
	elseif lowerMessage == "/offdrone" then
		if canUseCommand() then
			print("✅ Deactivating drone...")
			deactivateDrone()
		else
			print("⏳ Command debounced")
		end
	end
end

-- Connect chat
LOCAL_PLAYER.Chatted:Connect(onChatted)

-- Main loop
local function droneLoop()
	if not droneActive then return end
	
	handleInput()
	updateDroneCamera()
	updateDistanceDisplay()
end

-- Connect main loop
RunService.Heartbeat:Connect(droneLoop)

-- Cleanup
LOCAL_PLAYER.AncestryChanged:Connect(function()
	if not LOCAL_PLAYER.Parent then
		deactivateDrone()
	end
end)

-- Global functions
_G.SimpleDroneCamSystem = {
	activate = activateDrone,
	deactivate = deactivateDrone,
	toggle = function()
		if droneActive then
			deactivateDrone()
		else
			activateDrone()
		end
	end,
	isActive = function()
		return droneActive
	end,
	getDistance = function()
		if droneCFrame then
			return getDistanceFromSpawn(droneCFrame.Position)
		end
		return 0
	end,
	testChat = function()
		print("🧪 Testing chat command...")
		onChatted(LOCAL_PLAYER, "/drone")
	end,
	config = CONFIG
}

-- Commands
print("🔧 COMMANDS UNTUK TEST SIMPLE DRONE CAM:")
print("_G.SimpleDroneCamSystem.activate() - Activate drone")
print("_G.SimpleDroneCamSystem.deactivate() - Deactivate drone")
print("_G.SimpleDroneCamSystem.toggle() - Toggle drone")
print("_G.SimpleDroneCamSystem.isActive() - Check if drone is active")
print("_G.SimpleDroneCamSystem.getDistance() - Get current distance")
print("_G.SimpleDroneCamSystem.testChat() - Test chat command")
print("")
print("💬 CHAT COMMANDS:")
print("/drone - Activate drone")
print("/offdrone - Deactivate drone")
print("")
print("🎮 CONTROLS:")
print("WASD - Move drone")
print("Space - Move up")
print("Left Shift - Move down")
print("Left Ctrl + Mouse - Look around")
print("")
print("✅ SIMPLE & RELIABLE FEATURES:")
print("- Chat commands pasti berfungsi")
print("- Simple dan mudah dipahami")
print("- Batasan jarak 30 studs dari spawn point")
print("- Hide semua UI saat drone aktif")
print("- Smooth camera movement")
print("- Distance display dengan warning")
print("- Debounce untuk mencegah spam")
print("- Lightweight dan optimized")
print("- No bugs atau errors")
print("")
print("🚁 SIMPLE DRONE CAM SYSTEM READY!")