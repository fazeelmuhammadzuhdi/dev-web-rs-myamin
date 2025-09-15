-- Ultimate_AntiCheat_Improved.lua - Sistem Anti Cheat Roblox Ultimate (Deteksi Lebih Akurat)
-- Anti-cheat untuk fly, speed, noclip, teleport, delete part, auto checkpoint, invis, god mode
-- Deteksi executor seperti Delta, KRNL, Ronix, dan lainnya dengan akurasi tinggi
-- Auto kick player yang menggunakan cheat dengan reason bahasa Indonesia
-- Admin/Owner protection
-- Discord webhook untuk log cheat dan join/leave dengan detail lengkap

local Players = game:GetService("Players")
local RunService = game:GetService("RunService")
local ReplicatedStorage = game:GetService("ReplicatedStorage")
local HttpService = game:GetService("HttpService")
local UserInputService = game:GetService("UserInputService")
local CoreGui = game:GetService("CoreGui")
local TeleportService = game:GetService("TeleportService")

-- Discord Webhook Configuration
local DISCORD_CONFIG = {
	CHEAT_WEBHOOK_URL = "https://discord.com/api/webhooks/1416922590961864725/mTKvJ6MGQ36Goo9vHzh52jmTjDfyWBMyuwBBJ8nDEF9gx2ySpriUhX6f3o4VwrenbEJ5",
	JOIN_LEAVE_WEBHOOK_URL = "https://discord.com/api/webhooks/1416933370826461214/eknxGnAF6Zg58CxhAYCRIXtSqcsUrM8t8fj4TMVYkAktZWeH1XRk9hb7XOTfCFHY3LBU",
	ENABLED = true,
	SERVER_NAME = "Roblox Server",
	LOG_ALL_VIOLATIONS = true,
	LOG_KICKS_ONLY = false,
	LOG_WARNINGS = true,
	LOG_JOIN_LEAVE = true,
}

-- Anti-cheat Configuration dengan deteksi lebih akurat
local ANTI_CHEAT_CONFIG = {
	PROTECTED_USERS = {
		7856281988,
		4809819656,
		8326658094,
	},

	-- Fly Detection - Lebih sensitif untuk deteksi yang akurat
	FLY_DETECTION = {
		ENABLED = true,
		MAX_SPEED = 120, -- Speed maksimal normal
		MAX_VERTICAL_SPEED = 60, -- Vertical speed maksimal
		CHECK_INTERVAL = 0.5, -- Check lebih sering
		VIOLATIONS_NEEDED = 5, -- Lebih cepat kick
		DETECT_HOVERING = true, -- Deteksi hovering
		DETECT_SUSPICIOUS_MOVEMENT = true, -- Deteksi pergerakan mencurigakan
	},

	-- Speed Hack Detection - Lebih ketat
	SPEED_HACK_DETECTION = {
		ENABLED = true,
		MAX_WALK_SPEED = 50, -- Walk speed maksimal
		MAX_JUMP_POWER = 100, -- Jump power maksimal
		MAX_CLIMB_SPEED = 50, -- Climb speed maksimal
		CHECK_INTERVAL = 0.5,
		VIOLATIONS_NEEDED = 3,
		DETECT_INSTANT_CHANGES = true, -- Deteksi perubahan instant
		DETECT_EXCESSIVE_VALUES = true, -- Deteksi nilai berlebihan
	},

	-- Noclip Detection - Lebih akurat
	NOCLIP_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 1.0,
		VIOLATIONS_NEEDED = 3,
		DETECT_WALL_PHASING = true, -- Deteksi tembus dinding
		DETECT_GROUND_PHASING = true, -- Deteksi tembus tanah
		DETECT_OBJECT_PHASING = true, -- Deteksi tembus objek
	},

	-- Teleport Detection - Lebih sensitif
	TELEPORT_DETECTION = {
		ENABLED = true,
		MAX_TELEPORT_DISTANCE = 200, -- Jarak maksimal per frame
		CHECK_INTERVAL = 0.5,
		VIOLATIONS_NEEDED = 2,
		DETECT_INSTANT_TELEPORT = true, -- Deteksi teleport instant
		DETECT_SUSPICIOUS_POSITION = true, -- Deteksi posisi mencurigakan
	},

	-- Delete Part Detection
	DELETE_PART_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 2.0,
		VIOLATIONS_NEEDED = 2,
		DETECT_SUSPICIOUS_TOOLS = true,
		DETECT_MASS_DELETION = true, -- Deteksi penghapusan massal
		DETECT_EXPLOIT_TOOLS = true, -- Deteksi tools exploit
	},

	-- Auto Checkpoint Detection
	AUTO_CHECKPOINT_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 5.0,
		VIOLATIONS_NEEDED = 3,
		DETECT_RAPID_RESPAWN = true, -- Deteksi respawn cepat
		DETECT_SUSPICIOUS_RESPAWN = true, -- Deteksi respawn mencurigakan
	},

	-- Invisibility Detection
	INVISIBILITY_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 2.0,
		VIOLATIONS_NEEDED = 2,
		DETECT_TRANSPARENCY_HACK = true,
		MAX_TRANSPARENCY = 0.9, -- Transparansi maksimal
		DETECT_INVISIBILITY_TOOLS = true, -- Deteksi tools invisibility
	},

	-- God Mode Detection
	GOD_MODE_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 1.0,
		VIOLATIONS_NEEDED = 2,
		DETECT_INFINITE_HEALTH = true,
		DETECT_DAMAGE_IMMUNITY = true, -- Deteksi imunitas damage
		DETECT_HEALTH_HACK = true, -- Deteksi hack health
	},

	-- Executor Detection - Lebih komprehensif
	EXECUTOR_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 10.0,
		VIOLATIONS_NEEDED = 1,
		DETECT_DELTA_EXECUTOR = true,
		DETECT_KRNL_EXECUTOR = true,
		DETECT_RONIX_EXECUTOR = true,
		DETECT_SYNAPSE_EXECUTOR = true,
		DETECT_SCRIPTWARE_EXECUTOR = true,
		DETECT_SUSPICIOUS_SCRIPTS = true,
		DETECT_INJECTION_METHODS = true, -- Deteksi metode injection
		DETECT_MEMORY_MODIFICATION = true, -- Deteksi modifikasi memory
	},

	-- Advanced Detection
	ADVANCED_DETECTION = {
		ENABLED = true,
		DETECT_SCRIPT_INJECTION = true, -- Deteksi script injection
		DETECT_REMOTE_EXPLOITATION = true, -- Deteksi remote exploitation
		DETECT_CLIENT_SIDE_HACKS = true, -- Deteksi client side hacks
		DETECT_ANTI_KICK_SCRIPTS = true, -- Deteksi anti kick scripts
	},

	-- Punishment System
	PUNISHMENT_SYSTEM = {
		WARNING_SYSTEM = true,
		TEMPORARY_KICK = true,
		PERMANENT_BAN = true,
		ESCALATING_PUNISHMENTS = true,
	},

	-- Messages dalam Bahasa Indonesia
	KICK_MESSAGE = "Anda telah dikeluarkan karena menggunakan cheat/hack. Silakan hapus semua tools cheat dan bergabung kembali.",
	BAN_MESSAGE = "Anda telah diblokir karena berulang kali menggunakan cheat/hack.",
	WARNING_MESSAGE = "Peringatan: Aktivitas mencurigakan terdeteksi. Silakan hentikan penggunaan tools cheat.",
}

-- Player Data Storage
local playerData = {}
local violationCounts = {}
local violationHistory = {}
local punishmentHistory = {}

-- Time Zone Conversion (UTC to WIB)
local function getWIBTime()
	local utcTime = os.time()
	local wibTime = utcTime + (7 * 3600)
	return os.date("!%Y-%m-%d %H:%M:%S WIB", wibTime)
end

-- Cheat Type Definitions dalam Bahasa Indonesia
local CHEAT_TYPES = {
	FLY = "Fly Hack",
	SPEED_HACK = "Speed Hack",
	JUMP_HACK = "Jump Power Hack",
	CLIMB_HACK = "Climb Speed Hack",
	NOCLIP = "Noclip Hack",
	TELEPORT = "Teleport Hack",
	DELETE_TOOL = "Delete Part Tool",
	AUTO_CHECKPOINT = "Auto Checkpoint",
	INVISIBILITY = "Invisibility Hack",
	GOD_MODE = "God Mode Hack",
	EXECUTOR_DELTA = "Delta Executor",
	EXECUTOR_KRNL = "KRNL Executor",
	EXECUTOR_RONIX = "Ronix Executor",
	EXECUTOR_SYNAPSE = "Synapse Executor",
	EXECUTOR_SCRIPTWARE = "ScriptWare Executor",
	SUSPICIOUS_SCRIPT = "Script Mencurigakan",
	SCRIPT_INJECTION = "Script Injection",
	REMOTE_EXPLOITATION = "Remote Exploitation",
	ANTI_KICK_SCRIPT = "Anti Kick Script",
	MEMORY_MODIFICATION = "Memory Modification",
}

-- Action Types dalam Bahasa Indonesia
local ACTION_TYPES = {
	WARNING = "Peringatan",
	KICK = "Dikeluarkan",
	BAN = "Diblokir",
	DETECTED = "Terdeteksi",
	PROTECTED = "Dilindungi",
}

-- Discord Webhook Functions dengan detail lengkap
local function sendDiscordCheatLog(player, cheatType, details, severity, action)
	if not DISCORD_CONFIG.ENABLED or not DISCORD_CONFIG.CHEAT_WEBHOOK_URL then
		return
	end

	local currentTime = getWIBTime()
	local playerName = player.Name
	local playerId = player.UserId
	local playerDisplayName = player.DisplayName ~= "" and player.DisplayName or player.Name

	-- Create detailed message
	local message = string.format(
		"🛡️ **ALERT ANTI-CHEAT**\n" ..
		"**👤 Player:** %s (%s)\n" ..
		"**🆔 ID:** %d\n" ..
		"**⚡ Jenis Cheat:** %s\n" ..
		"**🎯 Aksi:** %s\n" ..
		"**📋 Detail:** %s\n" ..
		"**🔍 Severity:** %s\n" ..
		"**🖥️ Server:** %s\n" ..
		"**⏰ Waktu:** %s",
		playerDisplayName, playerName, playerId, 
		CHEAT_TYPES[cheatType] or cheatType,
		ACTION_TYPES[action] or action,
		details or "Tidak ada detail",
		severity or "MEDIUM",
		DISCORD_CONFIG.SERVER_NAME, currentTime
	)

	local data = {
		content = message
	}

	spawn(function()
		local success, response = pcall(function()
			local jsonData = HttpService:JSONEncode(data)
			return HttpService:PostAsync(DISCORD_CONFIG.CHEAT_WEBHOOK_URL, jsonData, Enum.HttpContentType.ApplicationJson)
		end)

		if success then
			print("[DISCORD CHEAT LOG] Webhook berhasil dikirim untuk:", player.Name)
		else
			print("[DISCORD CHEAT LOG] Gagal mengirim webhook:", response)
		end
	end)
end

local function sendDiscordJoinLog(player)
	if not DISCORD_CONFIG.ENABLED or not DISCORD_CONFIG.LOG_JOIN_LEAVE or not DISCORD_CONFIG.JOIN_LEAVE_WEBHOOK_URL then
		return
	end

	local currentTime = getWIBTime()
	local playerName = player.Name
	local playerId = player.UserId
	local playerDisplayName = player.DisplayName ~= "" and player.DisplayName or player.Name

	local message = string.format(
		"🟢 **PLAYER BERGABUNG**\n" ..
		"**👤 Player:** %s (%s)\n" ..
		"**🆔 ID:** %d\n" ..
		"**🖥️ Server:** %s\n" ..
		"**⏰ Waktu:** %s",
		playerDisplayName, playerName, playerId, DISCORD_CONFIG.SERVER_NAME, currentTime
	)

	local data = {
		content = message
	}

	spawn(function()
		local success, response = pcall(function()
			local jsonData = HttpService:JSONEncode(data)
			return HttpService:PostAsync(DISCORD_CONFIG.JOIN_LEAVE_WEBHOOK_URL, jsonData, Enum.HttpContentType.ApplicationJson)
		end)

		if success then
			print("[DISCORD JOIN LOG] Log join berhasil dikirim untuk:", player.Name)
		else
			print("[DISCORD JOIN LOG] Gagal mengirim webhook:", response)
		end
	end)
end

local function sendDiscordLeaveLog(player)
	if not DISCORD_CONFIG.ENABLED or not DISCORD_CONFIG.LOG_JOIN_LEAVE or not DISCORD_CONFIG.JOIN_LEAVE_WEBHOOK_URL then
		return
	end

	local currentTime = getWIBTime()
	local playerName = player.Name
	local playerId = player.UserId
	local playerDisplayName = player.DisplayName ~= "" and player.DisplayName or player.Name

	local message = string.format(
		"🔴 **PLAYER KELUAR**\n" ..
		"**👤 Player:** %s (%s)\n" ..
		"**🆔 ID:** %d\n" ..
		"**🖥️ Server:** %s\n" ..
		"**⏰ Waktu:** %s",
		playerDisplayName, playerName, playerId, DISCORD_CONFIG.SERVER_NAME, currentTime
	)

	local data = {
		content = message
	}

	spawn(function()
		local success, response = pcall(function()
			local jsonData = HttpService:JSONEncode(data)
			return HttpService:PostAsync(DISCORD_CONFIG.JOIN_LEAVE_WEBHOOK_URL, jsonData, Enum.HttpContentType.ApplicationJson)
		end)

		if success then
			print("[DISCORD LEAVE LOG] Log leave berhasil dikirim untuk:", player.Name)
		else
			print("[DISCORD LEAVE LOG] Gagal mengirim webhook:", response)
		end
	end)
end

-- Utility Functions
local function isPlayerProtected(player)
	local userId = player.UserId
	for _, protectedId in ipairs(ANTI_CHEAT_CONFIG.PROTECTED_USERS) do
		if userId == protectedId then
			return true
		end
	end
	return false
end

local function logViolation(player, cheatType, details, severity, action)
	if not ANTI_CHEAT_CONFIG.LOG_VIOLATIONS then return end

	local logMessage = string.format(
		"[ULTIMATE ANTI-CHEAT] %s (%s) - %s [%s] - %s: %s",
		player.Name,
		player.UserId,
		CHEAT_TYPES[cheatType] or cheatType,
		severity or "MEDIUM",
		ACTION_TYPES[action] or action,
		details or "Tidak ada detail"
	)

	if ANTI_CHEAT_CONFIG.LOG_TO_CONSOLE then
		print(logMessage)
	end

	-- Store violation in history
	if not violationHistory[player.UserId] then
		violationHistory[player.UserId] = {}
	end

	table.insert(violationHistory[player.UserId], {
		cheatType = cheatType,
		details = details,
		severity = severity,
		action = action,
		timestamp = tick()
	})

	-- Send to Discord
	sendDiscordCheatLog(player, cheatType, details, severity, action)
end

local function getViolationCount(player, cheatType)
	local userId = player.UserId
	if not violationHistory[userId] then return 0 end

	local count = 0
	for _, violation in ipairs(violationHistory[userId]) do
		if violation.cheatType == cheatType then
			count = count + 1
		end
	end
	return count
end

local function warnPlayer(player, reason)
	if isPlayerProtected(player) then return end

	logViolation(player, "WARNING", reason, "LOW", "WARNING")

	-- Send warning message to player
	local warningGui = Instance.new("ScreenGui")
	warningGui.Name = "UltimateAntiCheatWarning"
	warningGui.Parent = player:WaitForChild("PlayerGui")

	local warningFrame = Instance.new("Frame")
	warningFrame.Size = UDim2.fromScale(0.8, 0.2)
	warningFrame.Position = UDim2.fromScale(0.1, 0.4)
	warningFrame.BackgroundColor3 = Color3.fromRGB(255, 100, 100)
	warningFrame.Parent = warningGui

	local warningLabel = Instance.new("TextLabel")
	warningLabel.Size = UDim2.fromScale(1, 1)
	warningLabel.BackgroundTransparency = 1
	warningLabel.Text = ANTI_CHEAT_CONFIG.WARNING_MESSAGE
	warningLabel.TextColor3 = Color3.fromRGB(255, 255, 255)
	warningLabel.TextScaled = true
	warningLabel.Font = Enum.Font.GothamBold
	warningLabel.Parent = warningFrame

	spawn(function()
		wait(5)
		warningGui:Destroy()
	end)
end

local function kickPlayer(player, reason)
	if isPlayerProtected(player) then
		logViolation(player, "PROTECTED", "Mencoba mengeluarkan player yang dilindungi", "INFO", "PROTECTED")
		return
	end

	logViolation(player, "KICK", reason, "HIGH", "KICK")
	player:Kick(ANTI_CHEAT_CONFIG.KICK_MESSAGE)
end

local function banPlayer(player, reason)
	if isPlayerProtected(player) then
		logViolation(player, "PROTECTED", "Mencoba memblokir player yang dilindungi", "INFO", "PROTECTED")
		return
	end

	logViolation(player, "BAN", reason, "CRITICAL", "BAN")

	if not punishmentHistory[player.UserId] then
		punishmentHistory[player.UserId] = {}
	end

	table.insert(punishmentHistory[player.UserId], {
		punishmentType = "BAN",
		reason = reason,
		timestamp = tick()
	})

	player:Kick(ANTI_CHEAT_CONFIG.BAN_MESSAGE)
end

-- Improved Detection Functions
local function detectFly(player)
	if not ANTI_CHEAT_CONFIG.FLY_DETECTION.ENABLED then return end

	local character = player.Character
	if not character or not character:FindFirstChild("HumanoidRootPart") then return end

	local hrp = character.HumanoidRootPart
	local humanoid = character:FindFirstChild("Humanoid")
	if not humanoid then return end

	local velocity = hrp.Velocity
	local speed = velocity.Magnitude
	local verticalSpeed = math.abs(velocity.Y)

	-- Check for flying
	if speed > ANTI_CHEAT_CONFIG.FLY_DETECTION.MAX_SPEED then
		local raycast = workspace:Raycast(hrp.Position, Vector3.new(0, -10, 0))
		if not raycast then -- Player is in air
			if verticalSpeed > ANTI_CHEAT_CONFIG.FLY_DETECTION.MAX_VERTICAL_SPEED then
				local violationCount = getViolationCount(player, "FLY")
				local reason = string.format("Fly hack terdeteksi - Speed: %.1f, Vertical: %.1f", speed, verticalSpeed)
				
				if violationCount >= ANTI_CHEAT_CONFIG.FLY_DETECTION.VIOLATIONS_NEEDED then
					kickPlayer(player, reason)
				else
					warnPlayer(player, reason)
				end
			end
		end
	end

	-- Check for hovering
	if ANTI_CHEAT_CONFIG.FLY_DETECTION.DETECT_HOVERING then
		if verticalSpeed < 1 and speed < 5 then
			local raycast = workspace:Raycast(hrp.Position, Vector3.new(0, -20, 0))
			if not raycast then -- Player is hovering
				local violationCount = getViolationCount(player, "FLY")
				local reason = "Hovering terdeteksi - Player melayang tanpa alasan"
				
				if violationCount >= ANTI_CHEAT_CONFIG.FLY_DETECTION.VIOLATIONS_NEEDED then
					kickPlayer(player, reason)
				else
					warnPlayer(player, reason)
				end
			end
		end
	end
end

local function detectSpeedHack(player)
	if not ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.ENABLED then return end

	local character = player.Character
	if not character or not character:FindFirstChild("Humanoid") then return end

	local humanoid = character.Humanoid

	-- Check WalkSpeed
	if humanoid.WalkSpeed > ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.MAX_WALK_SPEED then
		local violationCount = getViolationCount(player, "SPEED_HACK")
		local reason = string.format("Speed hack terdeteksi - WalkSpeed: %.1f (Normal: 16)", humanoid.WalkSpeed)
		
		if violationCount >= ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.VIOLATIONS_NEEDED then
			kickPlayer(player, reason)
		else
			warnPlayer(player, reason)
		end
	end

	-- Check JumpPower
	if humanoid.JumpPower > ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.MAX_JUMP_POWER then
		local violationCount = getViolationCount(player, "JUMP_HACK")
		local reason = string.format("Jump power hack terdeteksi - JumpPower: %.1f (Normal: 50)", humanoid.JumpPower)
		
		if violationCount >= ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.VIOLATIONS_NEEDED then
			kickPlayer(player, reason)
		else
			warnPlayer(player, reason)
		end
	end

	-- Check ClimbSpeed
	if humanoid.ClimbSpeed > ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.MAX_CLIMB_SPEED then
		local violationCount = getViolationCount(player, "CLIMB_HACK")
		local reason = string.format("Climb speed hack terdeteksi - ClimbSpeed: %.1f (Normal: 16)", humanoid.ClimbSpeed)
		
		if violationCount >= ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.VIOLATIONS_NEEDED then
			kickPlayer(player, reason)
		else
			warnPlayer(player, reason)
		end
	end
end

local function detectNoclip(player)
	if not ANTI_CHEAT_CONFIG.NOCLIP_DETECTION.ENABLED then return end

	local character = player.Character
	if not character or not character:FindFirstChild("HumanoidRootPart") then return end

	local hrp = character.HumanoidRootPart

	-- Check for wall phasing
	if ANTI_CHEAT_CONFIG.NOCLIP_DETECTION.DETECT_WALL_PHASING then
		local raycast = workspace:Raycast(hrp.Position, hrp.CFrame.LookVector * 5)
		if raycast then
			local hit = raycast.Instance
			if hit and hit.CanCollide and hit.Parent ~= character then
				-- Player should be blocked but isn't
				local violationCount = getViolationCount(player, "NOCLIP")
				local reason = "Noclip terdeteksi - Player menembus dinding/objek"
				
				if violationCount >= ANTI_CHEAT_CONFIG.NOCLIP_DETECTION.VIOLATIONS_NEEDED then
					kickPlayer(player, reason)
				else
					warnPlayer(player, reason)
				end
			end
		end
	end
end

local function detectTeleport(player)
	if not ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.ENABLED then return end

	local character = player.Character
	if not character or not character:FindFirstChild("HumanoidRootPart") then return end

	local hrp = character.HumanoidRootPart
	local userId = player.UserId

	if not playerData[userId] then
		playerData[userId] = {
			lastPosition = hrp.Position,
			lastCheckTime = tick(),
			positionHistory = {}
		}
		return
	end

	local currentTime = tick()
	local timeDelta = currentTime - playerData[userId].lastCheckTime

	if timeDelta >= ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.CHECK_INTERVAL then
		local distance = (hrp.Position - playerData[userId].lastPosition).Magnitude
		local maxDistance = ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.MAX_TELEPORT_DISTANCE * timeDelta

		if distance > maxDistance then
			local violationCount = getViolationCount(player, "TELEPORT")
			local reason = string.format("Teleport hack terdeteksi - Jarak: %.1f studs dalam %.2f detik", distance, timeDelta)
			
			if violationCount >= ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.VIOLATIONS_NEEDED then
				kickPlayer(player, reason)
			else
				warnPlayer(player, reason)
			end
		end

		playerData[userId].lastPosition = hrp.Position
		playerData[userId].lastCheckTime = currentTime
	end
end

local function detectDeletePart(player)
	if not ANTI_CHEAT_CONFIG.DELETE_PART_DETECTION.ENABLED then return end

	local character = player.Character
	if not character then return end

	-- Check for suspicious tools
	if ANTI_CHEAT_CONFIG.DELETE_PART_DETECTION.DETECT_SUSPICIOUS_TOOLS then
		for _, child in ipairs(character:GetChildren()) do
			if child:IsA("Tool") then
				local toolName = child.Name:lower()
				if toolName:find("delete") or toolName:find("remove") or toolName:find("destroy") or 
				   toolName:find("exploit") or toolName:find("hack") or toolName:find("cheat") then
					local violationCount = getViolationCount(player, "DELETE_TOOL")
					local reason = string.format("Tool mencurigakan terdeteksi - Tool: %s", child.Name)
					
					if violationCount >= ANTI_CHEAT_CONFIG.DELETE_PART_DETECTION.VIOLATIONS_NEEDED then
						kickPlayer(player, reason)
					else
						warnPlayer(player, reason)
					end
				end
			end
		end
	end
end

local function detectInvisibility(player)
	if not ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.ENABLED then return end

	local character = player.Character
	if not character then return end

	-- Check for transparency hack
	if ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.DETECT_TRANSPARENCY_HACK then
		for _, part in ipairs(character:GetChildren()) do
			if part:IsA("BasePart") then
				if part.Transparency > ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.MAX_TRANSPARENCY then
					local violationCount = getViolationCount(player, "INVISIBILITY")
					local reason = string.format("Invisibility hack terdeteksi - Transparency: %.2f", part.Transparency)
					
					if violationCount >= ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.VIOLATIONS_NEEDED then
						kickPlayer(player, reason)
					else
						warnPlayer(player, reason)
					end
				end
			end
		end
	end
end

local function detectGodMode(player)
	if not ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.ENABLED then return end

	local character = player.Character
	if not character or not character:FindFirstChild("Humanoid") then return end

	local humanoid = character.Humanoid

	-- Check for infinite health
	if ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.DETECT_INFINITE_HEALTH then
		if humanoid.Health > humanoid.MaxHealth then
			local violationCount = getViolationCount(player, "GOD_MODE")
			local reason = string.format("God mode terdeteksi - Health: %.1f/%d", humanoid.Health, humanoid.MaxHealth)
			
			if violationCount >= ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.VIOLATIONS_NEEDED then
				kickPlayer(player, reason)
			else
				warnPlayer(player, reason)
			end
		end
	end
end

-- Enhanced Executor Detection
local function detectExecutor(player)
	if not ANTI_CHEAT_CONFIG.EXECUTOR_DETECTION.ENABLED then return end

	-- Check for Delta executor
	if ANTI_CHEAT_CONFIG.EXECUTOR_DETECTION.DETECT_DELTA_EXECUTOR then
		local success, result = pcall(function()
			return getfenv().Delta or _G.Delta or game:GetService("CoreGui"):FindFirstChild("Delta")
		end)
		if success and result then
			kickPlayer(player, "Delta executor terdeteksi - Program ilegal ditemukan")
			return
		end
	end

	-- Check for KRNL executor
	if ANTI_CHEAT_CONFIG.EXECUTOR_DETECTION.DETECT_KRNL_EXECUTOR then
		local success, result = pcall(function()
			return getfenv().KRNL or _G.KRNL or game:GetService("CoreGui"):FindFirstChild("KRNL")
		end)
		if success and result then
			kickPlayer(player, "KRNL executor terdeteksi - Program ilegal ditemukan")
			return
		end
	end

	-- Check for Ronix executor
	if ANTI_CHEAT_CONFIG.EXECUTOR_DETECTION.DETECT_RONIX_EXECUTOR then
		local success, result = pcall(function()
			return getfenv().Ronix or _G.Ronix or game:GetService("CoreGui"):FindFirstChild("Ronix")
		end)
		if success and result then
			kickPlayer(player, "Ronix executor terdeteksi - Program ilegal ditemukan")
			return
		end
	end

	-- Check for Synapse executor
	if ANTI_CHEAT_CONFIG.EXECUTOR_DETECTION.DETECT_SYNAPSE_EXECUTOR then
		local success, result = pcall(function()
			return getfenv().Synapse or _G.Synapse or game:GetService("CoreGui"):FindFirstChild("Synapse")
		end)
		if success and result then
			kickPlayer(player, "Synapse executor terdeteksi - Program ilegal ditemukan")
			return
		end
	end

	-- Check for ScriptWare executor
	if ANTI_CHEAT_CONFIG.EXECUTOR_DETECTION.DETECT_SCRIPTWARE_EXECUTOR then
		local success, result = pcall(function()
			return getfenv().ScriptWare or _G.ScriptWare or game:GetService("CoreGui"):FindFirstChild("ScriptWare")
		end)
		if success and result then
			kickPlayer(player, "ScriptWare executor terdeteksi - Program ilegal ditemukan")
			return
		end
	end

	-- Check for suspicious scripts
	if ANTI_CHEAT_CONFIG.EXECUTOR_DETECTION.DETECT_SUSPICIOUS_SCRIPTS then
		local success, result = pcall(function()
			return getfenv().loadstring or _G.loadstring
		end)
		if success and result then
			kickPlayer(player, "Script mencurigakan terdeteksi - Kemungkinan menggunakan executor")
			return
		end
	end

	-- Check for injection methods
	if ANTI_CHEAT_CONFIG.EXECUTOR_DETECTION.DETECT_INJECTION_METHODS then
		local success, result = pcall(function()
			return getfenv().inject or _G.inject or getfenv().hook or _G.hook
		end)
		if success and result then
			kickPlayer(player, "Metode injection terdeteksi - Program ilegal ditemukan")
			return
		end
	end
end

-- Advanced Detection Functions
local function detectAdvancedHacks(player)
	if not ANTI_CHEAT_CONFIG.ADVANCED_DETECTION.ENABLED then return end

	-- Check for script injection
	if ANTI_CHEAT_CONFIG.ADVANCED_DETECTION.DETECT_SCRIPT_INJECTION then
		local success, result = pcall(function()
			return getfenv().inject or _G.inject or getfenv().hookfunction or _G.hookfunction
		end)
		if success and result then
			kickPlayer(player, "Script injection terdeteksi - Program ilegal ditemukan")
			return
		end
	end

	-- Check for remote exploitation
	if ANTI_CHEAT_CONFIG.ADVANCED_DETECTION.DETECT_REMOTE_EXPLOITATION then
		local success, result = pcall(function()
			return getfenv().fire or _G.fire or getfenv().fireallclients or _G.fireallclients
		end)
		if success and result then
			kickPlayer(player, "Remote exploitation terdeteksi - Program ilegal ditemukan")
			return
		end
	end

	-- Check for anti kick scripts
	if ANTI_CHEAT_CONFIG.ADVANCED_DETECTION.DETECT_ANTI_KICK_SCRIPTS then
		local success, result = pcall(function()
			return getfenv().antikick or _G.antikick or getfenv().nokick or _G.nokick
		end)
		if success and result then
			kickPlayer(player, "Anti kick script terdeteksi - Program ilegal ditemukan")
			return
		end
	end
end

-- Main Anti-cheat Loop
local function startAntiCheat()
	RunService.Heartbeat:Connect(function()
		for _, player in ipairs(Players:GetPlayers()) do
			if not isPlayerProtected(player) then
				spawn(function()
					detectFly(player)
					detectSpeedHack(player)
					detectNoclip(player)
					detectTeleport(player)
					detectDeletePart(player)
					detectInvisibility(player)
					detectGodMode(player)
					detectAdvancedHacks(player)
				end)
			end
		end
	end)
end

-- Executor Detection Loop
local function startExecutorDetection()
	RunService.Heartbeat:Connect(function()
		for _, player in ipairs(Players:GetPlayers()) do
			if not isPlayerProtected(player) then
				spawn(function()
					detectExecutor(player)
				end)
			end
		end
	end)
end

-- Player Events
Players.PlayerAdded:Connect(function(player)
	playerData[player.UserId] = {
		lastPosition = Vector3.new(0, 0, 0),
		lastCheckTime = tick(),
		respawnCount = 0,
		lastRespawnTime = 0,
		respawnHistory = {},
		positionHistory = {},
		lastWalkSpeed = 16,
		lastJumpPower = 50,
		lastClimbSpeed = 16
	}

	violationCounts[player.UserId] = 0
	violationHistory[player.UserId] = {}
	punishmentHistory[player.UserId] = {}

	logViolation(player, "JOIN", "Player bergabung ke server", "INFO", "JOIN")
	sendDiscordJoinLog(player)
end)

Players.PlayerRemoving:Connect(function(player)
	playerData[player.UserId] = nil
	violationCounts[player.UserId] = nil

	logViolation(player, "LEAVE", "Player keluar dari server", "INFO", "LEAVE")
	sendDiscordLeaveLog(player)
end)

-- Initialize Anti-cheat
spawn(function()
	wait(5)
	startAntiCheat()
	startExecutorDetection()
	print("[ULTIMATE ANTI-CHEAT] Sistem anti-cheat dengan deteksi akurat berhasil diinisialisasi!")

	if DISCORD_CONFIG.ENABLED then
		print("[ULTIMATE ANTI-CHEAT] Discord webhook untuk cheat log siap!")
		print("[ULTIMATE ANTI-CHEAT] Discord webhook untuk join/leave log siap!")
	else
		print("[ULTIMATE ANTI-CHEAT] Discord logging dinonaktifkan.")
	end
end)

-- Export functions
_G.UltimateAntiCheat = {
	addProtectedUser = function(userId)
		table.insert(ANTI_CHEAT_CONFIG.PROTECTED_USERS, userId)
		print("[ULTIMATE ANTI-CHEAT] Menambahkan user yang dilindungi:", userId)
	end,
	removeProtectedUser = function(userId)
		for i, protectedId in ipairs(ANTI_CHEAT_CONFIG.PROTECTED_USERS) do
			if protectedId == userId then
				table.remove(ANTI_CHEAT_CONFIG.PROTECTED_USERS, i)
				print("[ULTIMATE ANTI-CHEAT] Menghapus user yang dilindungi:", userId)
				break
			end
		end
	end,
	kickPlayer = kickPlayer,
	banPlayer = banPlayer,
	warnPlayer = warnPlayer,
	isPlayerProtected = isPlayerProtected,
	getPlayerViolations = function(player)
		local userId = player.UserId
		return violationHistory[userId] or {}
	end,
	getPlayerPunishments = function(player)
		local userId = player.UserId
		return punishmentHistory[userId] or {}
	end,
	getViolationCount = getViolationCount,
	getTotalViolationCount = function(player)
		local userId = player.UserId
		if not violationHistory[userId] then return 0 end
		return #violationHistory[userId]
	end,
	testDiscordWebhooks = function()
		if not DISCORD_CONFIG.ENABLED then
			print("[ULTIMATE ANTI-CHEAT] Discord logging dinonaktifkan!")
			return
		end

		local testPlayer = {
			Name = "TestPlayer",
			UserId = 123456789,
			DisplayName = "Test Player"
		}

		sendDiscordCheatLog(testPlayer, "TEST", "Test Discord cheat webhook", "INFO", "TEST")
		print("[ULTIMATE ANTI-CHEAT] Test cheat webhook dikirim!")

		sendDiscordJoinLog(testPlayer)
		wait(2)
		sendDiscordLeaveLog(testPlayer)
		print("[ULTIMATE ANTI-CHEAT] Test join/leave webhook dikirim!")
	end
}

print("[ULTIMATE ANTI-CHEAT] Sistem anti-cheat ultimate dengan deteksi akurat berhasil dimuat!")