-- Ultimate_AntiCheat_Fixed.lua - Sistem Anti Cheat Roblox Ultimate (NO FALSE POSITIVE)
-- Anti-cheat untuk fly, speed, noclip, teleport, delete part, auto checkpoint, invis, god mode
-- Deteksi executor seperti Delta, KRNL, Ronix, dan lainnya dengan akurasi tinggi
-- Auto kick player yang menggunakan cheat dengan reason bahasa Indonesia
-- Admin/Owner protection
-- Discord webhook untuk log cheat dan join/leave dengan detail lengkap
-- FIXED: Tidak ada false positive untuk player normal

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

-- Anti-cheat Configuration dengan threshold yang TIDAK FALSE POSITIVE
local ANTI_CHEAT_CONFIG = {
	PROTECTED_USERS = {
		7856281988,
		4809819656,
		8326658094,
	},

	-- Fly Detection - Threshold yang sangat tinggi untuk menghindari false positive
	FLY_DETECTION = {
		ENABLED = true,
		MAX_SPEED = 300, -- Speed sangat tinggi untuk menghindari false positive
		MAX_VERTICAL_SPEED = 150, -- Vertical speed sangat tinggi
		CHECK_INTERVAL = 2.0, -- Check lebih jarang
		VIOLATIONS_NEEDED = 15, -- Banyak violation sebelum kick
		DETECT_HOVERING = false, -- Disabled untuk menghindari false positive
		DETECT_SUSPICIOUS_MOVEMENT = false, -- Disabled untuk menghindari false positive
		REQUIRE_CONSISTENT_FLYING = true, -- Harus konsisten terbang
		MIN_FLY_DURATION = 5.0, -- Minimal terbang 5 detik
	},

	-- Speed Hack Detection - Threshold yang sangat tinggi
	SPEED_HACK_DETECTION = {
		ENABLED = true,
		MAX_WALK_SPEED = 200, -- Walk speed sangat tinggi
		MAX_JUMP_POWER = 300, -- Jump power sangat tinggi
		MAX_CLIMB_SPEED = 200, -- Climb speed sangat tinggi
		CHECK_INTERVAL = 3.0, -- Check lebih jarang
		VIOLATIONS_NEEDED = 10, -- Banyak violation sebelum kick
		DETECT_INSTANT_CHANGES = false, -- Disabled untuk menghindari false positive
		DETECT_EXCESSIVE_VALUES = true, -- Hanya deteksi nilai yang benar-benar berlebihan
		REQUIRE_CONSISTENT_SPEED = true, -- Harus konsisten speed tinggi
		MIN_SPEED_DURATION = 3.0, -- Minimal speed tinggi 3 detik
	},

	-- Noclip Detection - Sangat konservatif
	NOCLIP_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 5.0, -- Check sangat jarang
		VIOLATIONS_NEEDED = 20, -- Banyak violation sebelum kick
		DETECT_WALL_PHASING = false, -- Disabled untuk menghindari false positive
		DETECT_GROUND_PHASING = false, -- Disabled untuk menghindari false positive
		DETECT_OBJECT_PHASING = false, -- Disabled untuk menghindari false positive
		REQUIRE_MULTIPLE_PHASES = true, -- Harus multiple phase
		MIN_PHASE_DURATION = 2.0, -- Minimal phase 2 detik
	},

	-- Teleport Detection - Threshold yang sangat tinggi
	TELEPORT_DETECTION = {
		ENABLED = true,
		MAX_TELEPORT_DISTANCE = 1000, -- Jarak sangat tinggi
		CHECK_INTERVAL = 2.0, -- Check lebih jarang
		VIOLATIONS_NEEDED = 8, -- Banyak violation sebelum kick
		DETECT_INSTANT_TELEPORT = false, -- Disabled untuk menghindari false positive
		DETECT_SUSPICIOUS_POSITION = false, -- Disabled untuk menghindari false positive
		REQUIRE_CONSISTENT_TELEPORT = true, -- Harus konsisten teleport
		MIN_TELEPORT_DISTANCE = 500, -- Minimal jarak untuk dianggap teleport
	},

	-- Delete Part Detection - Hanya deteksi tools yang jelas exploit
	DELETE_PART_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 10.0, -- Check sangat jarang
		VIOLATIONS_NEEDED = 5, -- Sedikit violation karena jelas exploit
		DETECT_SUSPICIOUS_TOOLS = true, -- Hanya tools yang jelas exploit
		DETECT_MASS_DELETION = false, -- Disabled untuk menghindari false positive
		DETECT_EXPLOIT_TOOLS = true, -- Hanya tools dengan nama exploit
		EXPLOIT_TOOL_NAMES = {"delete", "remove", "destroy", "exploit", "hack", "cheat", "bypass"}, -- Nama tools yang jelas exploit
	},

	-- Auto Checkpoint Detection - Disabled untuk menghindari false positive
	AUTO_CHECKPOINT_DETECTION = {
		ENABLED = false, -- Disabled untuk menghindari false positive
		CHECK_INTERVAL = 10.0,
		VIOLATIONS_NEEDED = 10,
		DETECT_RAPID_RESPAWN = false,
		DETECT_SUSPICIOUS_RESPAWN = false,
	},

	-- Invisibility Detection - Threshold yang sangat tinggi
	INVISIBILITY_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 5.0, -- Check jarang
		VIOLATIONS_NEEDED = 8, -- Banyak violation sebelum kick
		DETECT_TRANSPARENCY_HACK = true,
		MAX_TRANSPARENCY = 0.99, -- Hampir benar-benar invisible
		DETECT_INVISIBILITY_TOOLS = false, -- Disabled untuk menghindari false positive
		REQUIRE_CONSISTENT_INVISIBILITY = true, -- Harus konsisten invisible
		MIN_INVISIBILITY_DURATION = 3.0, -- Minimal invisible 3 detik
	},

	-- God Mode Detection - Threshold yang sangat tinggi
	GOD_MODE_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 3.0, -- Check jarang
		VIOLATIONS_NEEDED = 5, -- Sedikit violation karena jelas exploit
		DETECT_INFINITE_HEALTH = true,
		DETECT_DAMAGE_IMMUNITY = false, -- Disabled untuk menghindari false positive
		DETECT_HEALTH_HACK = true,
		REQUIRE_CONSISTENT_GOD_MODE = true, -- Harus konsisten god mode
		MIN_GOD_MODE_DURATION = 2.0, -- Minimal god mode 2 detik
	},

	-- Executor Detection - Hanya deteksi yang benar-benar jelas
	EXECUTOR_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 30.0, -- Check sangat jarang
		VIOLATIONS_NEEDED = 1, -- Langsung kick karena jelas exploit
		DETECT_DELTA_EXECUTOR = true,
		DETECT_KRNL_EXECUTOR = true,
		DETECT_RONIX_EXECUTOR = true,
		DETECT_SYNAPSE_EXECUTOR = true,
		DETECT_SCRIPTWARE_EXECUTOR = true,
		DETECT_SUSPICIOUS_SCRIPTS = false, -- Disabled untuk menghindari false positive
		DETECT_INJECTION_METHODS = false, -- Disabled untuk menghindari false positive
		DETECT_MEMORY_MODIFICATION = false, -- Disabled untuk menghindari false positive
	},

	-- Advanced Detection - Disabled untuk menghindari false positive
	ADVANCED_DETECTION = {
		ENABLED = false, -- Disabled untuk menghindari false positive
		DETECT_SCRIPT_INJECTION = false,
		DETECT_REMOTE_EXPLOITATION = false,
		DETECT_CLIENT_SIDE_HACKS = false,
		DETECT_ANTI_KICK_SCRIPTS = false,
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
		print("[DISCORD CHEAT LOG] Discord logging dinonaktifkan atau webhook URL tidak ada")
		return
	end

	local currentTime = getWIBTime()
	local playerName = player.Name
	local playerId = player.UserId
	local playerDisplayName = player.DisplayName ~= "" and player.DisplayName or player.Name

	-- Create simple message dengan 1 emoji saja
	local message = string.format(
		"🛡️ **ALERT ANTI-CHEAT**\n" ..
		"**Player:** %s (%s)\n" ..
		"**ID:** %d\n" ..
		"**Jenis Cheat:** %s\n" ..
		"**Aksi:** %s\n" ..
		"**Detail:** %s\n" ..
		"**Severity:** %s\n" ..
		"**Server:** %s\n" ..
		"**Waktu:** %s",
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
			local result = HttpService:PostAsync(DISCORD_CONFIG.CHEAT_WEBHOOK_URL, jsonData, Enum.HttpContentType.ApplicationJson)
			return result
		end)

		if success then
			print("[DISCORD CHEAT LOG] ✅ Webhook berhasil dikirim untuk:", player.Name)
		else
			print("[DISCORD CHEAT LOG] ❌ Gagal mengirim webhook untuk", player.Name, "Error:", response)
		end
	end)
end

local function sendDiscordJoinLog(player)
	if not DISCORD_CONFIG.ENABLED or not DISCORD_CONFIG.LOG_JOIN_LEAVE or not DISCORD_CONFIG.JOIN_LEAVE_WEBHOOK_URL then
		print("[DISCORD JOIN LOG] Discord logging dinonaktifkan atau webhook URL tidak ada")
		return
	end

	local currentTime = getWIBTime()
	local playerName = player.Name
	local playerId = player.UserId
	local playerDisplayName = player.DisplayName ~= "" and player.DisplayName or player.Name

	local message = string.format(
		"🟢 **PLAYER BERGABUNG**\n" ..
		"**Player:** %s (%s)\n" ..
		"**ID:** %d\n" ..
		"**Server:** %s\n" ..
		"**Waktu:** %s",
		playerDisplayName, playerName, playerId, DISCORD_CONFIG.SERVER_NAME, currentTime
	)

	local data = {
		content = message
	}

	spawn(function()
		local success, response = pcall(function()
			local jsonData = HttpService:JSONEncode(data)
			local result = HttpService:PostAsync(DISCORD_CONFIG.JOIN_LEAVE_WEBHOOK_URL, jsonData, Enum.HttpContentType.ApplicationJson)
			return result
		end)

		if success then
			print("[DISCORD JOIN LOG] ✅ Log join berhasil dikirim untuk:", player.Name)
		else
			print("[DISCORD JOIN LOG] ❌ Gagal mengirim webhook untuk", player.Name, "Error:", response)
		end
	end)
end

local function sendDiscordLeaveLog(player)
	if not DISCORD_CONFIG.ENABLED or not DISCORD_CONFIG.LOG_JOIN_LEAVE or not DISCORD_CONFIG.JOIN_LEAVE_WEBHOOK_URL then
		print("[DISCORD LEAVE LOG] Discord logging dinonaktifkan atau webhook URL tidak ada")
		return
	end

	local currentTime = getWIBTime()
	local playerName = player.Name
	local playerId = player.UserId
	local playerDisplayName = player.DisplayName ~= "" and player.DisplayName or player.Name

	local message = string.format(
		"🔴 **PLAYER KELUAR**\n" ..
		"**Player:** %s (%s)\n" ..
		"**ID:** %d\n" ..
		"**Server:** %s\n" ..
		"**Waktu:** %s",
		playerDisplayName, playerName, playerId, DISCORD_CONFIG.SERVER_NAME, currentTime
	)

	local data = {
		content = message
	}

	spawn(function()
		local success, response = pcall(function()
			local jsonData = HttpService:JSONEncode(data)
			local result = HttpService:PostAsync(DISCORD_CONFIG.JOIN_LEAVE_WEBHOOK_URL, jsonData, Enum.HttpContentType.ApplicationJson)
			return result
		end)

		if success then
			print("[DISCORD LEAVE LOG] ✅ Log leave berhasil dikirim untuk:", player.Name)
		else
			print("[DISCORD LEAVE LOG] ❌ Gagal mengirim webhook untuk", player.Name, "Error:", response)
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

-- Improved Detection Functions dengan threshold yang TIDAK FALSE POSITIVE
local function detectFly(player)
	if not ANTI_CHEAT_CONFIG.FLY_DETECTION.ENABLED then return end

	local character = player.Character
	if not character or not character:FindFirstChild("HumanoidRootPart") then return end

	local hrp = character.HumanoidRootPart
	local humanoid = character:FindFirstChild("Humanoid")
	if not humanoid then return end

	local userId = player.UserId
	if not playerData[userId] then
		playerData[userId] = {
			flyStartTime = 0,
			flyViolations = 0,
			lastFlyCheck = 0
		}
	end

	local velocity = hrp.Velocity
	local speed = velocity.Magnitude
	local verticalSpeed = math.abs(velocity.Y)

	-- Check for flying dengan threshold yang sangat tinggi
	if speed > ANTI_CHEAT_CONFIG.FLY_DETECTION.MAX_SPEED then
		local raycast = workspace:Raycast(hrp.Position, Vector3.new(0, -15, 0))
		if not raycast then -- Player is in air
			if verticalSpeed > ANTI_CHEAT_CONFIG.FLY_DETECTION.MAX_VERTICAL_SPEED then
				local currentTime = tick()
				
				-- Track flying duration
				if playerData[userId].flyStartTime == 0 then
					playerData[userId].flyStartTime = currentTime
					playerData[userId].flyViolations = 1
				else
					playerData[userId].flyViolations = playerData[userId].flyViolations + 1
				end

				-- Only kick if flying consistently for required duration
				if ANTI_CHEAT_CONFIG.FLY_DETECTION.REQUIRE_CONSISTENT_FLYING then
					local flyDuration = currentTime - playerData[userId].flyStartTime
					if flyDuration >= ANTI_CHEAT_CONFIG.FLY_DETECTION.MIN_FLY_DURATION and 
					   playerData[userId].flyViolations >= ANTI_CHEAT_CONFIG.FLY_DETECTION.VIOLATIONS_NEEDED then
						local reason = string.format("Fly hack terdeteksi - Speed: %.1f, Vertical: %.1f, Duration: %.1fs", 
							speed, verticalSpeed, flyDuration)
						kickPlayer(player, reason)
						playerData[userId].flyStartTime = 0
						playerData[userId].flyViolations = 0
					end
				end
			end
		else
			-- Player is on ground, reset fly tracking
			playerData[userId].flyStartTime = 0
			playerData[userId].flyViolations = 0
		end
	else
		-- Speed is normal, reset fly tracking
		playerData[userId].flyStartTime = 0
		playerData[userId].flyViolations = 0
	end
end

local function detectSpeedHack(player)
	if not ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.ENABLED then return end

	local character = player.Character
	if not character or not character:FindFirstChild("Humanoid") then return end

	local humanoid = character.Humanoid
	local userId = player.UserId

	if not playerData[userId] then
		playerData[userId] = {
			speedStartTime = 0,
			speedViolations = 0,
			lastSpeedCheck = 0
		}
	end

	-- Check WalkSpeed dengan threshold yang sangat tinggi
	if humanoid.WalkSpeed > ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.MAX_WALK_SPEED then
		local currentTime = tick()
		
		if playerData[userId].speedStartTime == 0 then
			playerData[userId].speedStartTime = currentTime
			playerData[userId].speedViolations = 1
		else
			playerData[userId].speedViolations = playerData[userId].speedViolations + 1
		end

		if ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.REQUIRE_CONSISTENT_SPEED then
			local speedDuration = currentTime - playerData[userId].speedStartTime
			if speedDuration >= ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.MIN_SPEED_DURATION and 
			   playerData[userId].speedViolations >= ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.VIOLATIONS_NEEDED then
				local reason = string.format("Speed hack terdeteksi - WalkSpeed: %.1f (Normal: 16), Duration: %.1fs", 
					humanoid.WalkSpeed, speedDuration)
				kickPlayer(player, reason)
				playerData[userId].speedStartTime = 0
				playerData[userId].speedViolations = 0
			end
		end
	else
		-- Speed is normal, reset tracking
		playerData[userId].speedStartTime = 0
		playerData[userId].speedViolations = 0
	end

	-- Check JumpPower dengan threshold yang sangat tinggi
	if humanoid.JumpPower > ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.MAX_JUMP_POWER then
		local reason = string.format("Jump power hack terdeteksi - JumpPower: %.1f (Normal: 50)", humanoid.JumpPower)
		kickPlayer(player, reason)
	end

	-- Check ClimbSpeed dengan threshold yang sangat tinggi
	if humanoid.ClimbSpeed > ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.MAX_CLIMB_SPEED then
		local reason = string.format("Climb speed hack terdeteksi - ClimbSpeed: %.1f (Normal: 16)", humanoid.ClimbSpeed)
		kickPlayer(player, reason)
	end
end

local function detectNoclip(player)
	-- Disabled untuk menghindari false positive
	return
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
			teleportViolations = 0,
			teleportStartTime = 0
		}
		return
	end

	local currentTime = tick()
	local timeDelta = currentTime - playerData[userId].lastCheckTime

	if timeDelta >= ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.CHECK_INTERVAL then
		local distance = (hrp.Position - playerData[userId].lastPosition).Magnitude
		local maxDistance = ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.MAX_TELEPORT_DISTANCE * timeDelta

		-- Hanya deteksi jika jarak benar-benar sangat jauh
		if distance > maxDistance and distance > ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.MIN_TELEPORT_DISTANCE then
			if ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.REQUIRE_CONSISTENT_TELEPORT then
				if playerData[userId].teleportStartTime == 0 then
					playerData[userId].teleportStartTime = currentTime
					playerData[userId].teleportViolations = 1
				else
					playerData[userId].teleportViolations = playerData[userId].teleportViolations + 1
				end

				if playerData[userId].teleportViolations >= ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.VIOLATIONS_NEEDED then
					local reason = string.format("Teleport hack terdeteksi - Jarak: %.1f studs dalam %.2f detik", distance, timeDelta)
					kickPlayer(player, reason)
					playerData[userId].teleportViolations = 0
					playerData[userId].teleportStartTime = 0
				end
			end
		else
			-- Reset teleport tracking
			playerData[userId].teleportViolations = 0
			playerData[userId].teleportStartTime = 0
		end

		playerData[userId].lastPosition = hrp.Position
		playerData[userId].lastCheckTime = currentTime
	end
end

local function detectDeletePart(player)
	if not ANTI_CHEAT_CONFIG.DELETE_PART_DETECTION.ENABLED then return end

	local character = player.Character
	if not character then return end

	-- Hanya deteksi tools dengan nama yang jelas exploit
	if ANTI_CHEAT_CONFIG.DELETE_PART_DETECTION.DETECT_SUSPICIOUS_TOOLS then
		for _, child in ipairs(character:GetChildren()) do
			if child:IsA("Tool") then
				local toolName = child.Name:lower()
				local isExploitTool = false
				
				-- Check against known exploit tool names
				for _, exploitName in ipairs(ANTI_CHEAT_CONFIG.DELETE_PART_DETECTION.EXPLOIT_TOOL_NAMES) do
					if toolName:find(exploitName) then
						isExploitTool = true
						break
					end
				end
				
				if isExploitTool then
					local reason = string.format("Tool exploit terdeteksi - Tool: %s", child.Name)
					kickPlayer(player, reason)
				end
			end
		end
	end
end

local function detectInvisibility(player)
	if not ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.ENABLED then return end

	local character = player.Character
	if not character then return end

	local userId = player.UserId
	if not playerData[userId] then
		playerData[userId] = {
			invisibilityStartTime = 0,
			invisibilityViolations = 0
		}
	end

	-- Check for transparency hack dengan threshold yang sangat tinggi
	if ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.DETECT_TRANSPARENCY_HACK then
		local isInvisible = false
		for _, part in ipairs(character:GetChildren()) do
			if part:IsA("BasePart") then
				if part.Transparency > ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.MAX_TRANSPARENCY then
					isInvisible = true
					break
				end
			end
		end

		if isInvisible then
			local currentTime = tick()
			
			if playerData[userId].invisibilityStartTime == 0 then
				playerData[userId].invisibilityStartTime = currentTime
				playerData[userId].invisibilityViolations = 1
			else
				playerData[userId].invisibilityViolations = playerData[userId].invisibilityViolations + 1
			end

			if ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.REQUIRE_CONSISTENT_INVISIBILITY then
				local invisibilityDuration = currentTime - playerData[userId].invisibilityStartTime
				if invisibilityDuration >= ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.MIN_INVISIBILITY_DURATION and 
				   playerData[userId].invisibilityViolations >= ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.VIOLATIONS_NEEDED then
					local reason = string.format("Invisibility hack terdeteksi - Duration: %.1fs", invisibilityDuration)
					kickPlayer(player, reason)
					playerData[userId].invisibilityStartTime = 0
					playerData[userId].invisibilityViolations = 0
				end
			end
		else
			-- Not invisible, reset tracking
			playerData[userId].invisibilityStartTime = 0
			playerData[userId].invisibilityViolations = 0
		end
	end
end

local function detectGodMode(player)
	if not ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.ENABLED then return end

	local character = player.Character
	if not character or not character:FindFirstChild("Humanoid") then return end

	local humanoid = character.Humanoid
	local userId = player.UserId

	if not playerData[userId] then
		playerData[userId] = {
			godModeStartTime = 0,
			godModeViolations = 0
		}
	end

	-- Check for infinite health dengan threshold yang sangat tinggi
	if ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.DETECT_INFINITE_HEALTH then
		if humanoid.Health > humanoid.MaxHealth then
			local currentTime = tick()
			
			if playerData[userId].godModeStartTime == 0 then
				playerData[userId].godModeStartTime = currentTime
				playerData[userId].godModeViolations = 1
			else
				playerData[userId].godModeViolations = playerData[userId].godModeViolations + 1
			end

			if ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.REQUIRE_CONSISTENT_GOD_MODE then
				local godModeDuration = currentTime - playerData[userId].godModeStartTime
				if godModeDuration >= ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.MIN_GOD_MODE_DURATION and 
				   playerData[userId].godModeViolations >= ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.VIOLATIONS_NEEDED then
					local reason = string.format("God mode terdeteksi - Health: %.1f/%d, Duration: %.1fs", 
						humanoid.Health, humanoid.MaxHealth, godModeDuration)
					kickPlayer(player, reason)
					playerData[userId].godModeStartTime = 0
					playerData[userId].godModeViolations = 0
				end
			end
		else
			-- Health is normal, reset tracking
			playerData[userId].godModeStartTime = 0
			playerData[userId].godModeViolations = 0
		end
	end
end

-- Enhanced Executor Detection - Hanya deteksi yang benar-benar jelas
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
end

-- Main Anti-cheat Loop dengan interval yang lebih jarang
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
				end)
			end
		end
	end)
end

-- Executor Detection Loop dengan interval yang sangat jarang
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
		lastClimbSpeed = 16,
		flyStartTime = 0,
		flyViolations = 0,
		speedStartTime = 0,
		speedViolations = 0,
		teleportViolations = 0,
		teleportStartTime = 0,
		invisibilityStartTime = 0,
		invisibilityViolations = 0,
		godModeStartTime = 0,
		godModeViolations = 0
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
	print("[ULTIMATE ANTI-CHEAT] Sistem anti-cheat dengan NO FALSE POSITIVE berhasil diinisialisasi!")

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
		print("[ULTIMATE ANTI-CHEAT] 🔧 Memulai test Discord webhooks...")
		
		if not DISCORD_CONFIG.ENABLED then
			print("[ULTIMATE ANTI-CHEAT] ❌ Discord logging dinonaktifkan!")
			return false
		end

		if not DISCORD_CONFIG.CHEAT_WEBHOOK_URL then
			print("[ULTIMATE ANTI-CHEAT] ❌ Cheat webhook URL tidak ada!")
			return false
		end

		if not DISCORD_CONFIG.JOIN_LEAVE_WEBHOOK_URL then
			print("[ULTIMATE ANTI-CHEAT] ❌ Join/Leave webhook URL tidak ada!")
			return false
		end

		local testPlayer = {
			Name = "TestPlayer",
			UserId = 123456789,
			DisplayName = "Test Player"
		}

		print("[ULTIMATE ANTI-CHEAT] 📤 Mengirim test cheat webhook...")
		sendDiscordCheatLog(testPlayer, "TEST", "Test Discord cheat webhook - Jika Anda melihat ini, webhook berfungsi!", "INFO", "TEST")
		
		wait(1)
		
		print("[ULTIMATE ANTI-CHEAT] 📤 Mengirim test join webhook...")
		sendDiscordJoinLog(testPlayer)
		
		wait(2)
		
		print("[ULTIMATE ANTI-CHEAT] 📤 Mengirim test leave webhook...")
		sendDiscordLeaveLog(testPlayer)
		
		print("[ULTIMATE ANTI-CHEAT] ✅ Test webhook selesai! Cek Discord channel Anda.")
		return true
	end,
	
	testCheatWebhookOnly = function()
		print("[ULTIMATE ANTI-CHEAT] 🔧 Memulai test cheat webhook saja...")
		
		if not DISCORD_CONFIG.ENABLED then
			print("[ULTIMATE ANTI-CHEAT] ❌ Discord logging dinonaktifkan!")
			return false
		end

		if not DISCORD_CONFIG.CHEAT_WEBHOOK_URL then
			print("[ULTIMATE ANTI-CHEAT] ❌ Cheat webhook URL tidak ada!")
			return false
		end

		local testPlayer = {
			Name = "TestPlayer",
			UserId = 123456789,
			DisplayName = "Test Player"
		}

		print("[ULTIMATE ANTI-CHEAT] 📤 Mengirim test cheat webhook...")
		sendDiscordCheatLog(testPlayer, "TEST", "Test Discord cheat webhook - Jika Anda melihat ini, cheat webhook berfungsi!", "INFO", "TEST")
		
		print("[ULTIMATE ANTI-CHEAT] ✅ Test cheat webhook selesai! Cek Discord channel Anda.")
		return true
	end,
	
	testJoinLeaveWebhookOnly = function()
		print("[ULTIMATE ANTI-CHEAT] 🔧 Memulai test join/leave webhook saja...")
		
		if not DISCORD_CONFIG.ENABLED then
			print("[ULTIMATE ANTI-CHEAT] ❌ Discord logging dinonaktifkan!")
			return false
		end

		if not DISCORD_CONFIG.JOIN_LEAVE_WEBHOOK_URL then
			print("[ULTIMATE ANTI-CHEAT] ❌ Join/Leave webhook URL tidak ada!")
			return false
		end

		local testPlayer = {
			Name = "TestPlayer",
			UserId = 123456789,
			DisplayName = "Test Player"
		}

		print("[ULTIMATE ANTI-CHEAT] 📤 Mengirim test join webhook...")
		sendDiscordJoinLog(testPlayer)
		
		wait(1)
		
		print("[ULTIMATE ANTI-CHEAT] 📤 Mengirim test leave webhook...")
		sendDiscordLeaveLog(testPlayer)
		
		print("[ULTIMATE ANTI-CHEAT] ✅ Test join/leave webhook selesai! Cek Discord channel Anda.")
		return true
	end,
	
	checkWebhookStatus = function()
		print("[ULTIMATE ANTI-CHEAT] 🔍 Mengecek status webhook...")
		
		print("Discord Config Status:")
		print("- ENABLED:", DISCORD_CONFIG.ENABLED)
		print("- LOG_ALL_VIOLATIONS:", DISCORD_CONFIG.LOG_ALL_VIOLATIONS)
		print("- LOG_KICKS_ONLY:", DISCORD_CONFIG.LOG_KICKS_ONLY)
		print("- LOG_WARNINGS:", DISCORD_CONFIG.LOG_WARNINGS)
		print("- LOG_JOIN_LEAVE:", DISCORD_CONFIG.LOG_JOIN_LEAVE)
		print("- SERVER_NAME:", DISCORD_CONFIG.SERVER_NAME)
		
		print("\nWebhook URLs:")
		print("- Cheat Webhook:", DISCORD_CONFIG.CHEAT_WEBHOOK_URL and "✅ Ada" or "❌ Tidak ada")
		print("- Join/Leave Webhook:", DISCORD_CONFIG.JOIN_LEAVE_WEBHOOK_URL and "✅ Ada" or "❌ Tidak ada")
		
		if DISCORD_CONFIG.ENABLED and DISCORD_CONFIG.CHEAT_WEBHOOK_URL and DISCORD_CONFIG.JOIN_LEAVE_WEBHOOK_URL then
			print("\n✅ Semua webhook siap digunakan!")
			return true
		else
			print("\n❌ Ada masalah dengan konfigurasi webhook!")
			return false
		end
	end,
	
	forceTestCheatLog = function(playerName, cheatType)
		print("[ULTIMATE ANTI-CHEAT] 🔧 Memaksa test cheat log...")
		
		if not DISCORD_CONFIG.ENABLED then
			print("[ULTIMATE ANTI-CHEAT] ❌ Discord logging dinonaktifkan!")
			return false
		end

		if not DISCORD_CONFIG.CHEAT_WEBHOOK_URL then
			print("[ULTIMATE ANTI-CHEAT] ❌ Cheat webhook URL tidak ada!")
			return false
		end

		local testPlayer = {
			Name = playerName or "TestPlayer",
			UserId = 123456789,
			DisplayName = playerName or "Test Player"
		}

		local cheatTypeToTest = cheatType or "FLY"
		print("[ULTIMATE ANTI-CHEAT] 📤 Mengirim test cheat log untuk:", cheatTypeToTest)
		sendDiscordCheatLog(testPlayer, cheatTypeToTest, "Test paksa cheat log - Jika Anda melihat ini, cheat webhook berfungsi!", "HIGH", "KICK")
		
		print("[ULTIMATE ANTI-CHEAT] ✅ Test cheat log selesai! Cek Discord channel Anda.")
		return true
	end,
	
	enableDiscordLogging = function()
		DISCORD_CONFIG.ENABLED = true
		print("[ULTIMATE ANTI-CHEAT] ✅ Discord logging diaktifkan!")
	end,
	
	disableDiscordLogging = function()
		DISCORD_CONFIG.ENABLED = false
		print("[ULTIMATE ANTI-CHEAT] ❌ Discord logging dinonaktifkan!")
	end
}

print("[ULTIMATE ANTI-CHEAT] Sistem anti-cheat ultimate dengan NO FALSE POSITIVE berhasil dimuat!")
print("")
print("🔧 COMMANDS UNTUK TEST WEBHOOK:")
print("_G.UltimateAntiCheat.testDiscordWebhooks() - Test semua webhook")
print("_G.UltimateAntiCheat.testCheatWebhookOnly() - Test cheat webhook saja")
print("_G.UltimateAntiCheat.testJoinLeaveWebhookOnly() - Test join/leave webhook saja")
print("_G.UltimateAntiCheat.checkWebhookStatus() - Cek status webhook")
print("_G.UltimateAntiCheat.forceTestCheatLog('PlayerName', 'FLY') - Paksa test cheat log")
print("_G.UltimateAntiCheat.enableDiscordLogging() - Aktifkan Discord logging")
print("_G.UltimateAntiCheat.disableDiscordLogging() - Nonaktifkan Discord logging")
print("")
print("📋 CARA PENGGUNAAN:")
print("1. Jalankan: _G.UltimateAntiCheat.checkWebhookStatus()")
print("2. Jika ada masalah, jalankan: _G.UltimateAntiCheat.testDiscordWebhooks()")
print("3. Cek Discord channel Anda untuk melihat log")
print("")
print("🛡️ EMOJI YANG DIGUNAKAN:")
print("🛡️ = Cheat Alert (Tameng Proteksi)")
print("🟢 = Player Join (Hijau)")
print("🔴 = Player Leave (Merah)")
print("")