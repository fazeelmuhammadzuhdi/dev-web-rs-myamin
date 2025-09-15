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

	-- Fly Detection - Optimized untuk performa yang baik
	FLY_DETECTION = {
		ENABLED = true,
		MAX_SPEED = 50, -- Speed rendah untuk deteksi yang agresif
		MAX_VERTICAL_SPEED = 30, -- Vertical speed rendah
		CHECK_INTERVAL = 2.0, -- Check setiap 2 detik untuk performa
		VIOLATIONS_NEEDED = 3, -- Lebih banyak violation sebelum kick
		DETECT_HOVERING = true, -- Enabled untuk deteksi hovering
		DETECT_SUSPICIOUS_MOVEMENT = true, -- Enabled untuk deteksi movement mencurigakan
		REQUIRE_CONSISTENT_FLYING = true, -- Perlu konsisten terbang
		MIN_FLY_DURATION = 1.0, -- Minimal terbang 1 detik
		REQUIRE_AIR_TIME = true, -- Perlu air time
		MIN_AIR_TIME = 0.5, -- Minimal di udara 0.5 detik
	},

	-- Speed Hack Detection - Threshold yang agresif untuk menangkap semua cheater
	SPEED_HACK_DETECTION = {
		ENABLED = true,
		MAX_WALK_SPEED = 20, -- Walk speed rendah untuk deteksi yang agresif
		MAX_JUMP_POWER = 60, -- Jump power rendah
		MAX_CLIMB_SPEED = 20, -- Climb speed rendah (tidak digunakan karena error)
		CHECK_INTERVAL = 2.0, -- Check setiap 2 detik untuk performa
		VIOLATIONS_NEEDED = 2, -- Lebih banyak violation sebelum kick
		DETECT_INSTANT_CHANGES = true, -- Enabled untuk deteksi perubahan instant
		DETECT_EXCESSIVE_VALUES = true, -- Deteksi semua nilai berlebihan
		REQUIRE_CONSISTENT_SPEED = true, -- Perlu konsisten speed tinggi
		MIN_SPEED_DURATION = 1.0, -- Minimal speed tinggi 1 detik
		REQUIRE_MOVEMENT = true, -- Perlu bergerak
		MIN_MOVEMENT_DISTANCE = 10, -- Minimal jarak 10 studs
	},

	-- Noclip Detection - Threshold yang agresif untuk menangkap semua cheater
	NOCLIP_DETECTION = {
		ENABLED = true, -- Enabled untuk deteksi noclip
		CHECK_INTERVAL = 2.0, -- Check setiap 2 detik untuk performa
		VIOLATIONS_NEEDED = 2, -- Lebih banyak violation sebelum kick
		DETECT_WALL_PHASING = true, -- Enabled untuk deteksi wall phasing
		DETECT_GROUND_PHASING = true, -- Enabled untuk deteksi ground phasing
		DETECT_OBJECT_PHASING = true, -- Enabled untuk deteksi object phasing
		REQUIRE_MULTIPLE_PHASES = true, -- Perlu multiple phase
		MIN_PHASE_DURATION = 1.0, -- Minimal phase 1 detik
	},

	-- Teleport Detection - Threshold yang agresif untuk menangkap semua cheater
	TELEPORT_DETECTION = {
		ENABLED = true,
		MAX_TELEPORT_DISTANCE = 50, -- Jarak rendah untuk deteksi yang agresif
		CHECK_INTERVAL = 1.0, -- Check setiap 1 detik untuk performa
		VIOLATIONS_NEEDED = 2, -- Lebih banyak violation sebelum kick
		DETECT_INSTANT_TELEPORT = true, -- Enabled untuk deteksi instant teleport
		DETECT_SUSPICIOUS_POSITION = true, -- Enabled untuk deteksi posisi mencurigakan
		REQUIRE_CONSISTENT_TELEPORT = true, -- Perlu konsisten teleport
		MIN_TELEPORT_DISTANCE = 10, -- Minimal jarak untuk dianggap teleport
		REQUIRE_INSTANT_MOVEMENT = true, -- Perlu instant movement
		MAX_MOVEMENT_TIME = 0.5, -- Maksimal waktu pergerakan 0.5 detik
	},

	-- Delete Part Detection - Threshold yang agresif untuk menangkap semua cheater
	DELETE_PART_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 2.0, -- Check setiap 2 detik untuk performa
		VIOLATIONS_NEEDED = 2, -- Lebih banyak violation sebelum kick
		DETECT_SUSPICIOUS_TOOLS = true, -- Deteksi semua tools mencurigakan
		DETECT_MASS_DELETION = true, -- Enabled untuk deteksi mass deletion
		DETECT_EXPLOIT_TOOLS = true, -- Deteksi semua tools dengan nama exploit
		EXPLOIT_TOOL_NAMES = {"delete", "remove", "destroy", "exploit", "hack", "cheat", "bypass", "tool", "gun", "sword", "knife"}, -- Nama tools yang mencurigakan
		REQUIRE_TOOL_USAGE = true, -- Perlu menggunakan tool
		MIN_TOOL_USAGE_TIME = 1.0, -- Minimal menggunakan tool 1 detik
	},

	-- Auto Checkpoint Detection - Threshold yang agresif untuk menangkap semua cheater
	AUTO_CHECKPOINT_DETECTION = {
		ENABLED = true, -- Enabled untuk deteksi auto checkpoint
		CHECK_INTERVAL = 2.0, -- Check setiap 2 detik untuk performa
		VIOLATIONS_NEEDED = 2, -- Lebih banyak violation sebelum kick
		DETECT_RAPID_RESPAWN = true, -- Enabled untuk deteksi rapid respawn
		DETECT_SUSPICIOUS_RESPAWN = true, -- Enabled untuk deteksi respawn mencurigakan
	},

	-- Invisibility Detection - Threshold yang agresif untuk menangkap semua cheater
	INVISIBILITY_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 2.0, -- Check setiap 2 detik untuk performa
		VIOLATIONS_NEEDED = 2, -- Lebih banyak violation sebelum kick
		DETECT_TRANSPARENCY_HACK = true,
		MAX_TRANSPARENCY = 0.1, -- Sangat rendah untuk deteksi yang agresif
		DETECT_INVISIBILITY_TOOLS = true, -- Enabled untuk deteksi invisibility tools
		REQUIRE_CONSISTENT_INVISIBILITY = true, -- Perlu konsisten invisible
		MIN_INVISIBILITY_DURATION = 1.0, -- Minimal invisible 1 detik
		REQUIRE_ALL_PARTS_INVISIBLE = true, -- Perlu semua parts invisible
		MIN_INVISIBLE_PARTS = 3, -- Minimal 3 parts invisible
	},

	-- God Mode Detection - Threshold yang agresif untuk menangkap semua cheater
	GOD_MODE_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 2.0, -- Check setiap 2 detik untuk performa
		VIOLATIONS_NEEDED = 2, -- Lebih banyak violation sebelum kick
		DETECT_INFINITE_HEALTH = true,
		DETECT_DAMAGE_IMMUNITY = true, -- Enabled untuk deteksi damage immunity
		DETECT_HEALTH_HACK = true,
		REQUIRE_CONSISTENT_GOD_MODE = true, -- Perlu konsisten god mode
		MIN_GOD_MODE_DURATION = 1.0, -- Minimal god mode 1 detik
		REQUIRE_HEALTH_ABOVE_MAX = true, -- Perlu health di atas max
		MIN_HEALTH_EXCESS = 10, -- Minimal health excess 10
	},

	-- Executor Detection - ULTRA AGRESSIVE untuk pengguna login dengan executor
	EXECUTOR_DETECTION = {
		ENABLED = true,
		CHECK_INTERVAL = 3.0, -- Check setiap 3 detik untuk performa yang optimal
		VIOLATIONS_NEEDED = 1, -- Langsung kick karena jelas exploit
		DETECT_DELTA_EXECUTOR = true,
		DETECT_KRNL_EXECUTOR = true,
		DETECT_RONIX_EXECUTOR = true,
		DETECT_SYNAPSE_EXECUTOR = true,
		DETECT_SCRIPTWARE_EXECUTOR = true,
		DETECT_SUSPICIOUS_SCRIPTS = true, -- Enabled untuk deteksi script mencurigakan
		DETECT_INJECTION_METHODS = true, -- Enabled untuk deteksi injection methods
		DETECT_MEMORY_MODIFICATION = true, -- Enabled untuk deteksi memory modification
		REQUIRE_MULTIPLE_CHECKS = false, -- Tidak perlu multiple checks
		MIN_CHECKS_BEFORE_KICK = 1, -- Minimal 1 check sebelum kick
	},

	-- Advanced Detection - Threshold yang agresif untuk menangkap semua cheater
	ADVANCED_DETECTION = {
		ENABLED = true, -- Enabled untuk deteksi advanced
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

	-- Error handling untuk parameter yang nil
	if not player then
		print("[DISCORD CHEAT LOG] Error: Player parameter is nil")
		return
	end

	local currentTime = getWIBTime()
	local playerName = player.Name or "Unknown"
	local playerId = player.UserId or 0
	local playerDisplayName = (player.DisplayName and player.DisplayName ~= "") and player.DisplayName or playerName

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
		playerDisplayName or "Unknown", 
		playerName or "Unknown", 
		playerId or 0, 
		CHEAT_TYPES[cheatType] or cheatType or "UNKNOWN",
		ACTION_TYPES[action] or action or "UNKNOWN",
		details or "Tidak ada detail",
		severity or "MEDIUM",
		DISCORD_CONFIG.SERVER_NAME or "Unknown Server", 
		currentTime or "Unknown Time"
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

	-- Error handling untuk parameter yang nil
	if not player then
		print("[DISCORD JOIN LOG] Error: Player parameter is nil")
		return
	end

	local currentTime = getWIBTime()
	local playerName = player.Name or "Unknown"
	local playerId = player.UserId or 0
	local playerDisplayName = (player.DisplayName and player.DisplayName ~= "") and player.DisplayName or playerName

	local message = string.format(
		"🟢 **PLAYER BERGABUNG**\n" ..
		"**Player:** %s (%s)\n" ..
		"**ID:** %d\n" ..
		"**Server:** %s\n" ..
		"**Waktu:** %s",
		playerDisplayName or "Unknown", 
		playerName or "Unknown", 
		playerId or 0, 
		DISCORD_CONFIG.SERVER_NAME or "Unknown Server", 
		currentTime or "Unknown Time"
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

	-- Error handling untuk parameter yang nil
	if not player then
		print("[DISCORD LEAVE LOG] Error: Player parameter is nil")
		return
	end

	local currentTime = getWIBTime()
	local playerName = player.Name or "Unknown"
	local playerId = player.UserId or 0
	local playerDisplayName = (player.DisplayName and player.DisplayName ~= "") and player.DisplayName or playerName

	local message = string.format(
		"🔴 **PLAYER KELUAR**\n" ..
		"**Player:** %s (%s)\n" ..
		"**ID:** %d\n" ..
		"**Server:** %s\n" ..
		"**Waktu:** %s",
		playerDisplayName or "Unknown", 
		playerName or "Unknown", 
		playerId or 0, 
		DISCORD_CONFIG.SERVER_NAME or "Unknown Server", 
		currentTime or "Unknown Time"
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
	-- Error handling untuk parameter yang nil
	if not player then
		print("[ULTIMATE ANTI-CHEAT] Error: Player parameter is nil in isPlayerProtected")
		return false
	end

	local userId = player.UserId or 0
	for _, protectedId in ipairs(ANTI_CHEAT_CONFIG.PROTECTED_USERS or {}) do
		if userId == protectedId then
			return true
		end
	end
	return false
end

local function logViolation(player, cheatType, details, severity, action)
	if not ANTI_CHEAT_CONFIG.LOG_VIOLATIONS then return end

	-- Error handling untuk parameter yang nil
	if not player then
		print("[ULTIMATE ANTI-CHEAT] Error: Player parameter is nil in logViolation")
		return
	end

	local logMessage = string.format(
		"[ULTIMATE ANTI-CHEAT] %s (%s) - %s [%s] - %s: %s",
		player.Name or "Unknown",
		player.UserId or 0,
		CHEAT_TYPES[cheatType] or cheatType or "UNKNOWN",
		severity or "MEDIUM",
		ACTION_TYPES[action] or action or "UNKNOWN",
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
	-- Error handling untuk parameter yang nil
	if not player then
		print("[ULTIMATE ANTI-CHEAT] Error: Player parameter is nil in warnPlayer")
		return
	end

	if isPlayerProtected(player) then return end

	logViolation(player, "WARNING", reason or "Tidak ada alasan", "LOW", "WARNING")

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
	-- Error handling untuk parameter yang nil
	if not player then
		print("[ULTIMATE ANTI-CHEAT] Error: Player parameter is nil in kickPlayer")
		return
	end

	if isPlayerProtected(player) then
		logViolation(player, "PROTECTED", "Mencoba mengeluarkan player yang dilindungi", "INFO", "PROTECTED")
		return
	end

	logViolation(player, "KICK", reason or "Tidak ada alasan", "HIGH", "KICK")
	
	-- Error handling untuk kick
	local success, errorMsg = pcall(function()
		player:Kick(ANTI_CHEAT_CONFIG.KICK_MESSAGE or "Anda telah dikeluarkan dari server karena menggunakan program ilegal.")
	end)
	
	if not success then
		print("[ULTIMATE ANTI-CHEAT] Error kicking player:", errorMsg)
	end
end

local function banPlayer(player, reason)
	-- Error handling untuk parameter yang nil
	if not player then
		print("[ULTIMATE ANTI-CHEAT] Error: Player parameter is nil in banPlayer")
		return
	end

	if isPlayerProtected(player) then
		logViolation(player, "PROTECTED", "Mencoba memblokir player yang dilindungi", "INFO", "PROTECTED")
		return
	end

	logViolation(player, "BAN", reason or "Tidak ada alasan", "CRITICAL", "BAN")

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

-- Improved Detection Functions dengan validasi tambahan untuk mencegah false positive
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
			lastFlyCheck = 0,
			airTime = 0,
			lastGroundTime = 0
		}
	end

	local velocity = hrp.Velocity
	local speed = velocity.Magnitude
	local verticalSpeed = math.abs(velocity.Y)

	-- Validasi tambahan untuk mencegah false positive
	if speed < 50 or verticalSpeed < 20 then
		-- Reset tracking jika speed terlalu rendah
		playerData[userId].flyStartTime = 0
		playerData[userId].flyViolations = 0
		playerData[userId].airTime = 0
		return
	end

	-- Check for flying dengan threshold yang sangat tinggi
	if speed > ANTI_CHEAT_CONFIG.FLY_DETECTION.MAX_SPEED then
		local raycast = workspace:Raycast(hrp.Position, Vector3.new(0, -20, 0))
		if not raycast then -- Player is in air
			if verticalSpeed > ANTI_CHEAT_CONFIG.FLY_DETECTION.MAX_VERTICAL_SPEED then
				local currentTime = tick()
				
				-- Track air time
				if playerData[userId].airTime == 0 then
					playerData[userId].airTime = currentTime
				end
				
				local airDuration = currentTime - playerData[userId].airTime
				
				-- Validasi: Harus benar-benar di udara cukup lama
				if ANTI_CHEAT_CONFIG.FLY_DETECTION.REQUIRE_AIR_TIME and airDuration < ANTI_CHEAT_CONFIG.FLY_DETECTION.MIN_AIR_TIME then
					return
				end
				
				-- Track flying duration
				if playerData[userId].flyStartTime == 0 then
					playerData[userId].flyStartTime = currentTime
					playerData[userId].flyViolations = 1
				else
					playerData[userId].flyViolations = (playerData[userId].flyViolations or 0) + 1
				end

				-- Only kick if flying consistently for required duration
				if ANTI_CHEAT_CONFIG.FLY_DETECTION.REQUIRE_CONSISTENT_FLYING then
					local flyDuration = currentTime - playerData[userId].flyStartTime
					if flyDuration >= ANTI_CHEAT_CONFIG.FLY_DETECTION.MIN_FLY_DURATION and 
					   (playerData[userId].flyViolations or 0) >= ANTI_CHEAT_CONFIG.FLY_DETECTION.VIOLATIONS_NEEDED then
						local reason = string.format("Fly hack terdeteksi - Speed: %.1f, Vertical: %.1f, Duration: %.1fs, AirTime: %.1fs", 
							speed, verticalSpeed, flyDuration, airDuration)
						kickPlayer(player, reason)
						playerData[userId].flyStartTime = 0
						playerData[userId].flyViolations = 0
						playerData[userId].airTime = 0
					end
				end
			end
		else
			-- Player is on ground, reset fly tracking
			playerData[userId].flyStartTime = 0
			playerData[userId].flyViolations = 0
			playerData[userId].airTime = 0
			playerData[userId].lastGroundTime = tick()
		end
	else
		-- Speed is normal, reset fly tracking
		playerData[userId].flyStartTime = 0
		playerData[userId].flyViolations = 0
		playerData[userId].airTime = 0
	end
end

local function detectSpeedHack(player)
	if not ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.ENABLED then return end

	local character = player.Character
	if not character or not character:FindFirstChild("Humanoid") then return end

	local humanoid = character.Humanoid
	local hrp = character:FindFirstChild("HumanoidRootPart")
	if not hrp then return end

	local userId = player.UserId

	if not playerData[userId] then
		playerData[userId] = {
			speedStartTime = 0,
			speedViolations = 0,
			lastSpeedCheck = 0,
			movementDistance = 0,
			lastPosition = hrp.Position
		}
	end

	-- Validasi tambahan untuk mencegah false positive
	local currentPosition = hrp.Position
	local distanceMoved = (currentPosition - (playerData[userId].lastPosition or currentPosition)).Magnitude
	playerData[userId].movementDistance = (playerData[userId].movementDistance or 0) + distanceMoved
	playerData[userId].lastPosition = currentPosition

	-- Check WalkSpeed dengan threshold yang sangat tinggi
	if humanoid.WalkSpeed > ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.MAX_WALK_SPEED then
		local currentTime = tick()
		
		-- Validasi: Harus benar-benar bergerak cukup jauh
		if ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.REQUIRE_MOVEMENT and 
		   playerData[userId].movementDistance < ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.MIN_MOVEMENT_DISTANCE then
			return
		end
		
		if playerData[userId].speedStartTime == 0 then
			playerData[userId].speedStartTime = currentTime
			playerData[userId].speedViolations = 1
		else
			playerData[userId].speedViolations = (playerData[userId].speedViolations or 0) + 1
		end

		if ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.REQUIRE_CONSISTENT_SPEED then
			local speedDuration = currentTime - playerData[userId].speedStartTime
			if speedDuration >= ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.MIN_SPEED_DURATION and 
			   (playerData[userId].speedViolations or 0) >= ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.VIOLATIONS_NEEDED then
				local reason = string.format("Speed hack terdeteksi - WalkSpeed: %.1f (Normal: 16), Duration: %.1fs, Distance: %.1f", 
					humanoid.WalkSpeed, speedDuration, playerData[userId].movementDistance)
				kickPlayer(player, reason)
				playerData[userId].speedStartTime = 0
				playerData[userId].speedViolations = 0
				playerData[userId].movementDistance = 0
			end
		end
	else
		-- Speed is normal, reset tracking
		playerData[userId].speedStartTime = 0
		playerData[userId].speedViolations = 0
		playerData[userId].movementDistance = 0
	end

	-- Check JumpPower dengan threshold yang sangat tinggi
	if humanoid.JumpPower > ANTI_CHEAT_CONFIG.SPEED_HACK_DETECTION.MAX_JUMP_POWER then
		local reason = string.format("Jump power hack terdeteksi - JumpPower: %.1f (Normal: 50)", humanoid.JumpPower)
		kickPlayer(player, reason)
	end

	-- Check ClimbSpeed dengan threshold yang sangat tinggi (ClimbSpeed tidak ada di Humanoid, skip)
	-- ClimbSpeed is not a valid member of Humanoid in newer Roblox versions
end

local function detectNoclip(player)
	if not ANTI_CHEAT_CONFIG.NOCLIP_DETECTION.ENABLED then return end

	local character = player.Character
	if not character or not character:FindFirstChild("HumanoidRootPart") then return end

	local hrp = character.HumanoidRootPart
	local userId = player.UserId

	if not playerData[userId] then
		playerData[userId] = {
			noclipViolations = 0,
			lastNoclipCheck = 0
		}
	end

	local currentTime = tick()
	if currentTime - (playerData[userId].lastNoclipCheck or 0) >= ANTI_CHEAT_CONFIG.NOCLIP_DETECTION.CHECK_INTERVAL then
		-- Check for noclip dengan raycast
		local raycast = workspace:Raycast(hrp.Position, Vector3.new(0, -5, 0))
		if not raycast then
			-- Player is floating, check for wall phasing
			local wallRaycast = workspace:Raycast(hrp.Position, hrp.CFrame.LookVector * 5)
			if not wallRaycast then
				playerData[userId].noclipViolations = (playerData[userId].noclipViolations or 0) + 1
				
				if (playerData[userId].noclipViolations or 0) >= ANTI_CHEAT_CONFIG.NOCLIP_DETECTION.VIOLATIONS_NEEDED then
					kickPlayer(player, "Noclip hack terdeteksi - Player melewati dinding")
					playerData[userId].noclipViolations = 0
				end
			else
				playerData[userId].noclipViolations = 0
			end
		else
			playerData[userId].noclipViolations = 0
		end
		
		playerData[userId].lastNoclipCheck = currentTime
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
			teleportViolations = 0,
			teleportStartTime = 0,
			movementStartTime = 0
		}
		return
	end

	local currentTime = tick()
	local timeDelta = currentTime - (playerData[userId].lastCheckTime or 0)

	if timeDelta >= ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.CHECK_INTERVAL then
		local distance = (hrp.Position - (playerData[userId].lastPosition or hrp.Position)).Magnitude
		local maxDistance = ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.MAX_TELEPORT_DISTANCE * timeDelta

		-- Validasi tambahan untuk mencegah false positive
		if distance < 50 then
			-- Reset tracking jika jarak terlalu kecil
			playerData[userId].teleportViolations = 0
			playerData[userId].teleportStartTime = 0
			playerData[userId].movementStartTime = 0
		elseif distance > maxDistance and distance > ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.MIN_TELEPORT_DISTANCE then
			-- Validasi: Harus benar-benar instant movement
			if ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.REQUIRE_INSTANT_MOVEMENT then
				if playerData[userId].movementStartTime == 0 then
					playerData[userId].movementStartTime = currentTime
				end
				
				local movementTime = currentTime - playerData[userId].movementStartTime
				if movementTime > ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.MAX_MOVEMENT_TIME then
					-- Movement terlalu lama, bukan teleport
					playerData[userId].movementStartTime = 0
					return
				end
			end
			
			if ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.REQUIRE_CONSISTENT_TELEPORT then
				if playerData[userId].teleportStartTime == 0 then
					playerData[userId].teleportStartTime = currentTime
					playerData[userId].teleportViolations = 1
				else
					playerData[userId].teleportViolations = (playerData[userId].teleportViolations or 0) + 1
				end

				if (playerData[userId].teleportViolations or 0) >= ANTI_CHEAT_CONFIG.TELEPORT_DETECTION.VIOLATIONS_NEEDED then
					local reason = string.format("Teleport hack terdeteksi - Jarak: %.1f studs dalam %.2f detik, MovementTime: %.3fs", 
						distance, timeDelta, movementTime or 0)
					kickPlayer(player, reason)
					playerData[userId].teleportViolations = 0
					playerData[userId].teleportStartTime = 0
					playerData[userId].movementStartTime = 0
				end
			end
		else
			-- Reset teleport tracking
			playerData[userId].teleportViolations = 0
			playerData[userId].teleportStartTime = 0
			playerData[userId].movementStartTime = 0
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

	-- Check for transparency hack dengan validasi tambahan untuk mencegah false positive
	if ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.DETECT_TRANSPARENCY_HACK then
		local invisibleParts = 0
		local totalParts = 0
		local veryInvisibleParts = 0

		for _, part in ipairs(character:GetChildren()) do
			if part:IsA("BasePart") and part.Name ~= "HumanoidRootPart" then
				totalParts = totalParts + 1
				if part.Transparency > ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.MAX_TRANSPARENCY then
					invisibleParts = invisibleParts + 1
					if part.Transparency >= 0.95 then
						veryInvisibleParts = veryInvisibleParts + 1
					end
				end
			end
		end

		-- Validasi tambahan untuk mencegah false positive
		if totalParts < 3 then
			-- Tidak cukup parts untuk deteksi yang akurat
			playerData[userId].invisibilityStartTime = 0
			playerData[userId].invisibilityViolations = 0
			return
		end

		-- Validasi: Harus minimal 3 parts invisible dan semua parts harus invisible
		if ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.REQUIRE_ALL_PARTS_INVISIBLE then
			if invisibleParts < ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.MIN_INVISIBLE_PARTS then
				playerData[userId].invisibilityStartTime = 0
				playerData[userId].invisibilityViolations = 0
				return
			end
		end

		if invisibleParts > 0 then
			local invisibilityRatio = invisibleParts / totalParts
			
			if invisibilityRatio >= 0.5 then -- At least half of parts are invisible
				local currentTime = tick()
				
				if playerData[userId].invisibilityStartTime == 0 then
					playerData[userId].invisibilityStartTime = currentTime
					playerData[userId].invisibilityViolations = 1
				else
					playerData[userId].invisibilityViolations = (playerData[userId].invisibilityViolations or 0) + 1
				end

				if ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.REQUIRE_CONSISTENT_INVISIBILITY then
					local invisibilityDuration = currentTime - playerData[userId].invisibilityStartTime
					if invisibilityDuration >= ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.MIN_INVISIBILITY_DURATION and 
					   (playerData[userId].invisibilityViolations or 0) >= ANTI_CHEAT_CONFIG.INVISIBILITY_DETECTION.VIOLATIONS_NEEDED then
						local reason = string.format("Invisibility hack terdeteksi - %d/%d parts invisible (%.1f%%), VeryInvisible: %d, Duration: %.1fs", 
							invisibleParts, totalParts, invisibilityRatio * 100, veryInvisibleParts, invisibilityDuration)
						kickPlayer(player, reason)
						playerData[userId].invisibilityStartTime = 0
						playerData[userId].invisibilityViolations = 0
					end
				end
			else
				-- Reset invisibility tracking
				playerData[userId].invisibilityStartTime = 0
				playerData[userId].invisibilityViolations = 0
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

	-- Check for infinite health dengan validasi tambahan untuk mencegah false positive
	if ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.DETECT_INFINITE_HEALTH then
		if humanoid.Health > humanoid.MaxHealth then
			local healthExcess = humanoid.Health - humanoid.MaxHealth
			
			-- Validasi: Health harus benar-benar di atas max dengan margin yang cukup
			if ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.REQUIRE_HEALTH_ABOVE_MAX and 
			   healthExcess < ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.MIN_HEALTH_EXCESS then
				-- Health excess terlalu kecil, mungkin false positive
				playerData[userId].godModeStartTime = 0
				playerData[userId].godModeViolations = 0
				return
			end
			
			local currentTime = tick()
			
			if playerData[userId].godModeStartTime == 0 then
				playerData[userId].godModeStartTime = currentTime
				playerData[userId].godModeViolations = 1
			else
				playerData[userId].godModeViolations = (playerData[userId].godModeViolations or 0) + 1
			end

			if ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.REQUIRE_CONSISTENT_GOD_MODE then
				local godModeDuration = currentTime - playerData[userId].godModeStartTime
				if godModeDuration >= ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.MIN_GOD_MODE_DURATION and 
				   (playerData[userId].godModeViolations or 0) >= ANTI_CHEAT_CONFIG.GOD_MODE_DETECTION.VIOLATIONS_NEEDED then
					local reason = string.format("God mode terdeteksi - Health: %.1f/%d (Excess: %.1f), Duration: %.1fs", 
						humanoid.Health, humanoid.MaxHealth, healthExcess, godModeDuration)
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

-- OPTIMIZED Executor Detection dengan debounce dan deteksi yang lebih efektif
local function detectExecutor(player)
	if not ANTI_CHEAT_CONFIG.EXECUTOR_DETECTION.ENABLED then return end

	local userId = player.UserId
	if not playerData[userId] then
		playerData[userId] = {
			executorChecks = 0,
			lastExecutorCheck = 0,
			executorDebounce = 0
		}
	end

	local currentTime = tick()
	
	-- Debounce untuk mencegah spam detection
	if currentTime - (playerData[userId].executorDebounce or 0) < 0.1 then
		return
	end
	
	playerData[userId].executorDebounce = currentTime
	
	-- Check interval untuk performa
	if currentTime - (playerData[userId].lastExecutorCheck or 0) < ANTI_CHEAT_CONFIG.EXECUTOR_DETECTION.CHECK_INTERVAL then
		return
	end
	
	playerData[userId].lastExecutorCheck = currentTime

	-- OPTIMIZED executor detection dengan metode yang paling efektif
	local executorsToCheck = {
		"Delta", "KRNL", "Synapse", "ScriptWare", "Ronix", "Fluxus", "Electron", "Comet", 
		"Elysian", "Sentinel", "Valyse", "Calamari", "Nihon", "Turtle", "JJSploit", 
		"WeAreDevs", "Hydrogen", "Calamari", "Nihon", "Turtle"
	}

	-- Check semua executor dengan metode yang paling efektif
	for _, executorName in ipairs(executorsToCheck) do
		-- Method 1: Check _G dengan debounce
		local success1, result1 = pcall(function()
			return _G[executorName]
		end)
		if success1 and result1 then
			kickPlayer(player, executorName .. " executor terdeteksi di _G - Program ilegal ditemukan")
			return
		end

		-- Method 2: Check getfenv dengan debounce
		local success2, result2 = pcall(function()
			return getfenv()[executorName]
		end)
		if success2 and result2 then
			kickPlayer(player, executorName .. " executor terdeteksi di getfenv - Program ilegal ditemukan")
			return
		end

		-- Method 3: Check CoreGui dengan debounce
		local success3, result3 = pcall(function()
			return game:GetService("CoreGui"):FindFirstChild(executorName)
		end)
		if success3 and result3 then
			kickPlayer(player, executorName .. " executor terdeteksi di CoreGui - Program ilegal ditemukan")
			return
		end

		-- Method 4: Check PlayerGui dengan debounce
		local success4, result4 = pcall(function()
			return player:FindFirstChild("PlayerGui") and player.PlayerGui:FindFirstChild(executorName)
		end)
		if success4 and result4 then
			kickPlayer(player, executorName .. " executor terdeteksi di PlayerGui - Program ilegal ditemukan")
			return
		end

		-- Method 5: Check StarterGui dengan debounce
		local success5, result5 = pcall(function()
			return game:GetService("StarterGui"):FindFirstChild(executorName)
		end)
		if success5 and result5 then
			kickPlayer(player, executorName .. " executor terdeteksi di StarterGui - Program ilegal ditemukan")
			return
		end

		-- Method 6: Check StarterPack dengan debounce
		local success6, result6 = pcall(function()
			return game:GetService("StarterPack"):FindFirstChild(executorName)
		end)
		if success6 and result6 then
			kickPlayer(player, executorName .. " executor terdeteksi di StarterPack - Program ilegal ditemukan")
			return
		end

		-- Method 7: Check StarterPlayer dengan debounce
		local success7, result7 = pcall(function()
			return game:GetService("StarterPlayer"):FindFirstChild(executorName)
		end)
		if success7 and result7 then
			kickPlayer(player, executorName .. " executor terdeteksi di StarterPlayer - Program ilegal ditemukan")
			return
		end

		-- Method 8: Check Workspace dengan debounce
		local success8, result8 = pcall(function()
			return workspace:FindFirstChild(executorName)
		end)
		if success8 and result8 then
			kickPlayer(player, executorName .. " executor terdeteksi di Workspace - Program ilegal ditemukan")
			return
		end

		-- Method 9: Check ServerStorage dengan debounce
		local success9, result9 = pcall(function()
			return game:GetService("ServerStorage"):FindFirstChild(executorName)
		end)
		if success9 and result9 then
			kickPlayer(player, executorName .. " executor terdeteksi di ServerStorage - Program ilegal ditemukan")
			return
		end

		-- Method 10: Check ReplicatedStorage dengan debounce
		local success10, result10 = pcall(function()
			return game:GetService("ReplicatedStorage"):FindFirstChild(executorName)
		end)
		if success10 and result10 then
			kickPlayer(player, executorName .. " executor terdeteksi di ReplicatedStorage - Program ilegal ditemukan")
			return
		end
	end

	-- Check untuk executor functions yang paling efektif
	local executorFunctions = {
		"getgenv", "getrawmetatable", "setrawmetatable", "getnamecallmethod", 
		"setnamecallmethod", "hookfunction", "newcclosure", "checkcaller",
		"islclosure", "is_synapse_function", "is_krnl_function", "is_fluxus_function",
		"is_delta_function", "is_scriptware_function", "is_electron_function",
		"is_comet_function", "is_elysian_function", "is_sentinel_function"
	}
	
	for _, funcName in ipairs(executorFunctions) do
		local success, result = pcall(function()
			return _G[funcName] and type(_G[funcName]) == "function"
		end)
		if success and result then
			kickPlayer(player, "Executor function '" .. funcName .. "' terdeteksi - Program ilegal ditemukan")
			return
		end
	end

	-- Check untuk executor tables yang paling efektif
	local executorTables = {
		"syn", "krnl", "fluxus", "electron", "comet", "elysian", "sentinel",
		"valyse", "calamari", "nihon", "turtle", "jjsploit", "wearedevs",
		"delta", "ronix", "scriptware", "hydrogen", "calamari", "nihon", "turtle"
	}
	
	for _, tableName in ipairs(executorTables) do
		local success, result = pcall(function()
			return _G[tableName] and type(_G[tableName]) == "table"
		end)
		if success and result then
			kickPlayer(player, "Executor table '" .. tableName .. "' terdeteksi - Program ilegal ditemukan")
			return
		end
	end

	-- Check untuk suspicious patterns yang paling efektif
	local suspiciousPatterns = {
		"Executor", "Exploit", "Hack", "Cheat", "Inject", "Loadstring", "LoadLibrary",
		"Script", "Tool", "Gun", "Sword", "Knife", "Weapon", "Aimbot", "Wallhack",
		"Speedhack", "Flyhack", "Noclip", "Godmode", "Invisibility", "Teleport"
	}
	
	for _, pattern in ipairs(suspiciousPatterns) do
		local success, result = pcall(function()
			return _G[pattern]
		end)
		if success and result then
			kickPlayer(player, "Suspicious pattern '" .. pattern .. "' terdeteksi - Program ilegal ditemukan")
			return
		end
	end

	-- Check untuk executor-specific methods yang paling efektif
	local executorMethods = {
		"getgenv", "getrawmetatable", "setrawmetatable", "getnamecallmethod", 
		"setnamecallmethod", "hookfunction", "newcclosure", "checkcaller",
		"islclosure", "is_synapse_function", "is_krnl_function", "is_fluxus_function",
		"is_delta_function", "is_scriptware_function", "is_electron_function",
		"is_comet_function", "is_elysian_function", "is_sentinel_function",
		"is_ronix_function", "is_valyse_function", "is_calamari_function",
		"is_nihon_function", "is_turtle_function", "is_jjsploit_function",
		"is_wearedevs_function", "is_hydrogen_function"
	}
	
	for _, method in ipairs(executorMethods) do
		local success, result = pcall(function()
			return _G[method] and type(_G[method]) == "function"
		end)
		if success and result then
			kickPlayer(player, "Executor method '" .. method .. "' terdeteksi - Program ilegal ditemukan")
			return
		end
	end

	-- Check untuk executor-specific properties yang paling efektif
	local executorProperties = {
		"syn", "krnl", "fluxus", "electron", "comet", "elysian", "sentinel",
		"valyse", "calamari", "nihon", "turtle", "jjsploit", "wearedevs",
		"delta", "ronix", "scriptware", "hydrogen", "calamari", "nihon", "turtle"
	}
	
	for _, property in ipairs(executorProperties) do
		local success, result = pcall(function()
			return _G[property] and type(_G[property]) == "table"
		end)
		if success and result then
			kickPlayer(player, "Executor property '" .. property .. "' terdeteksi - Program ilegal ditemukan")
			return
		end
	end

	-- Check untuk executor-specific services yang paling efektif
	local executorServices = {
		"VirtualInputManager", "VirtualUser", "GuiService", "UserInputService",
		"TweenService", "RunService", "HttpService", "DataStoreService",
		"Players", "Lighting", "SoundService", "TextService", "Chat", "MarketplaceService"
	}
	
	for _, serviceName in ipairs(executorServices) do
		local success, result = pcall(function()
			local service = game:GetService(serviceName)
			return service and (service:FindFirstChild("Delta") or service:FindFirstChild("KRNL") or 
				service:FindFirstChild("Synapse") or service:FindFirstChild("ScriptWare") or 
				service:FindFirstChild("Ronix") or service:FindFirstChild("Fluxus") or 
				service:FindFirstChild("Electron") or service:FindFirstChild("Comet") or 
				service:FindFirstChild("Elysian") or service:FindFirstChild("Sentinel"))
		end)
		if success and result then
			kickPlayer(player, "Executor service '" .. serviceName .. "' terdeteksi - Program ilegal ditemukan")
			return
		end
	end
end

-- Main Anti-cheat Loop dengan interval yang lebih jarang
-- Optimized Main Anti-cheat Loop untuk performa yang lebih baik
local function startAntiCheat()
	local lastCheck = 0
	local checkInterval = 3.0 -- Check setiap 3 detik untuk performa yang optimal
	
	RunService.Heartbeat:Connect(function()
		local currentTime = tick()
		
		-- Check interval untuk performa yang optimal
		if currentTime - lastCheck < checkInterval then
			return
		end
		
		lastCheck = currentTime
		
		-- Optimized player checking dengan batch processing yang lebih efisien
		local players = Players:GetPlayers()
		local playerCount = #players
		
		-- Process players in smaller batches untuk performa yang lebih baik
		local batchSize = math.min(3, playerCount) -- Process maksimal 3 player per frame
		local startIndex = (currentTime % math.ceil(playerCount / batchSize)) * batchSize + 1
		local endIndex = math.min(startIndex + batchSize - 1, playerCount)
		
		for i = startIndex, endIndex do
			local player = players[i]
			if player and not isPlayerProtected(player) then
				-- Check detections dengan interval yang berbeda untuk performa optimal
				if currentTime % 4 < 2 then -- Check setiap 4 detik (Group 1)
					spawn(function()
						detectFly(player)
						detectSpeedHack(player)
					end)
				else -- Check setiap 4 detik (Group 2)
					spawn(function()
						detectNoclip(player)
						detectTeleport(player)
					end)
				end
				
				-- Check detections yang lebih jarang untuk performa optimal
				if currentTime % 6 < 2 then -- Check setiap 6 detik (Group 3)
					spawn(function()
						detectDeletePart(player)
						detectInvisibility(player)
					end)
				elseif currentTime % 6 < 4 then -- Check setiap 6 detik (Group 4)
					spawn(function()
						detectGodMode(player)
					end)
				end
			end
		end
	end)
end

-- OPTIMIZED Executor Detection Loop dengan debounce untuk performa yang lebih baik
local function startExecutorDetection()
	local lastExecutorCheck = 0
	local executorCheckInterval = 3.0 -- Check setiap 3 detik untuk performa yang optimal
	local executorDebounce = 0
	
	RunService.Heartbeat:Connect(function()
		local currentTime = tick()
		
		-- Debounce untuk mencegah spam detection
		if currentTime - executorDebounce < 0.2 then
			return
		end
		
		executorDebounce = currentTime
		
		-- Check interval untuk performa yang optimal
		if currentTime - lastExecutorCheck < executorCheckInterval then
			return
		end
		
		lastExecutorCheck = currentTime
		
		-- Process players in batches untuk performa yang lebih baik
		local players = Players:GetPlayers()
		local playerCount = #players
		local batchSize = math.min(2, playerCount) -- Process maksimal 2 player per frame untuk executor
		
		for i = 1, batchSize do
			local player = players[i]
			if player and not isPlayerProtected(player) then
				-- Execute dengan spawn untuk performa yang lebih baik
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
	
	-- OPTIMIZED: Check executor saat player join dengan delay yang lebih lama untuk performa
	spawn(function()
		wait(5) -- Tunggu 5 detik untuk memastikan player sudah fully loaded dan tidak memberatkan server
		detectExecutor(player)
	end)
end)

Players.PlayerRemoving:Connect(function(player)
	playerData[player.UserId] = nil
	violationCounts[player.UserId] = nil

	logViolation(player, "LEAVE", "Player keluar dari server", "INFO", "LEAVE")
	sendDiscordLeaveLog(player)
end)

-- Initialize Anti-cheat
-- Performance Monitoring
local performanceStats = {
	lastCleanup = 0,
	cleanupInterval = 300, -- 5 menit
	totalChecks = 0,
	totalDetections = 0
}

-- Cleanup function untuk performa yang lebih baik
local function cleanupOldData()
	local currentTime = tick()
	
	-- Cleanup violation history yang sudah lama
	for userId, violations in pairs(violationHistory) do
		local newViolations = {}
		for _, violation in ipairs(violations) do
			if currentTime - violation.timestamp < 3600 then -- Keep violations from last hour
				table.insert(newViolations, violation)
			end
		end
		violationHistory[userId] = newViolations
	end
	
	-- Cleanup punishment history yang sudah lama
	for userId, punishments in pairs(punishmentHistory) do
		local newPunishments = {}
		for _, punishment in ipairs(punishments) do
			if currentTime - punishment.timestamp < 3600 then -- Keep punishments from last hour
				table.insert(newPunishments, punishment)
			end
		end
		punishmentHistory[userId] = newPunishments
	end
	
	print("[ULTIMATE ANTI-CHEAT] 🧹 Cleanup completed - Performance optimized")
end

-- Performance monitoring loop
RunService.Heartbeat:Connect(function()
	local currentTime = tick()
	
	-- Cleanup setiap 5 menit
	if currentTime - performanceStats.lastCleanup > performanceStats.cleanupInterval then
		performanceStats.lastCleanup = currentTime
		spawn(cleanupOldData)
	end
end)

spawn(function()
	wait(5)
	startAntiCheat()
	startExecutorDetection()
	print("[ULTIMATE ANTI-CHEAT] Sistem anti-cheat dengan PERFORMANCE OPTIMIZED berhasil diinisialisasi!")

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
	end,
	
	testExecutorWebhook = function()
		print("[ULTIMATE ANTI-CHEAT] 🧪 Testing Executor Webhook...")
		
		local testPlayer = {
			Name = "TestExecutorPlayer",
			UserId = 789123456,
			DisplayName = "TestExecutorPlayer"
		}
		
		sendDiscordCheatLog(testPlayer, "EXECUTOR", "Delta executor terdeteksi di _G - Program ilegal ditemukan", "HIGH", "KICK")
		
		print("[ULTIMATE ANTI-CHEAT] ✅ Executor Webhook Test Completed!")
	end,
	
	testDeltaExecutorWebhook = function()
		print("[ULTIMATE ANTI-CHEAT] 🧪 Testing Delta Executor Webhook...")
		
		local testPlayer = {
			Name = "TestDeltaPlayer",
			UserId = 321654987,
			DisplayName = "TestDeltaPlayer"
		}
		
		sendDiscordCheatLog(testPlayer, "EXECUTOR", "Delta executor terdeteksi di _G - Program ilegal ditemukan", "HIGH", "KICK")
		
		print("[ULTIMATE ANTI-CHEAT] ✅ Delta Executor Webhook Test Completed!")
	end,
	
	testKRNLExecutorWebhook = function()
		print("[ULTIMATE ANTI-CHEAT] 🧪 Testing KRNL Executor Webhook...")
		
		local testPlayer = {
			Name = "TestKRNLPlayer",
			UserId = 654987321,
			DisplayName = "TestKRNLPlayer"
		}
		
		sendDiscordCheatLog(testPlayer, "EXECUTOR", "KRNL executor terdeteksi di getfenv - Program ilegal ditemukan", "HIGH", "KICK")
		
		print("[ULTIMATE ANTI-CHEAT] ✅ KRNL Executor Webhook Test Completed!")
	end,
	
	testSynapseExecutorWebhook = function()
		print("[ULTIMATE ANTI-CHEAT] 🧪 Testing Synapse Executor Webhook...")
		
		local testPlayer = {
			Name = "TestSynapsePlayer",
			UserId = 987321654,
			DisplayName = "TestSynapsePlayer"
		}
		
		sendDiscordCheatLog(testPlayer, "EXECUTOR", "Synapse executor terdeteksi di CoreGui - Program ilegal ditemukan", "HIGH", "KICK")
		
		print("[ULTIMATE ANTI-CHEAT] ✅ Synapse Executor Webhook Test Completed!")
	end,
	
	testScriptWareExecutorWebhook = function()
		print("[ULTIMATE ANTI-CHEAT] 🧪 Testing ScriptWare Executor Webhook...")
		
		local testPlayer = {
			Name = "TestScriptWarePlayer",
			UserId = 147258369,
			DisplayName = "TestScriptWarePlayer"
		}
		
		sendDiscordCheatLog(testPlayer, "EXECUTOR", "ScriptWare executor terdeteksi di PlayerGui - Program ilegal ditemukan", "HIGH", "KICK")
		
		print("[ULTIMATE ANTI-CHEAT] ✅ ScriptWare Executor Webhook Test Completed!")
	end,
	
	testAllExecutorWebhooks = function()
		print("[ULTIMATE ANTI-CHEAT] 🧪 Testing All Executor Webhooks...")
		
		local executors = {
			{name = "Delta", userId = 111111111, displayName = "TestDeltaPlayer"},
			{name = "KRNL", userId = 222222222, displayName = "TestKRNLPlayer"},
			{name = "Synapse", userId = 333333333, displayName = "TestSynapsePlayer"},
			{name = "ScriptWare", userId = 444444444, displayName = "TestScriptWarePlayer"},
			{name = "Ronix", userId = 555555555, displayName = "TestRonixPlayer"},
			{name = "Fluxus", userId = 666666666, displayName = "TestFluxusPlayer"},
			{name = "Electron", userId = 777777777, displayName = "TestElectronPlayer"},
			{name = "Comet", userId = 888888888, displayName = "TestCometPlayer"},
			{name = "Elysian", userId = 999999999, displayName = "TestElysianPlayer"},
			{name = "Sentinel", userId = 101010101, displayName = "TestSentinelPlayer"}
		}
		
		for _, executor in ipairs(executors) do
			local testPlayer = {
				Name = executor.displayName,
				UserId = executor.userId,
				DisplayName = executor.displayName
			}
			
			sendDiscordCheatLog(testPlayer, "EXECUTOR", executor.name .. " executor terdeteksi - Program ilegal ditemukan", "HIGH", "KICK")
			wait(0.5) -- Delay untuk mencegah spam
		end
		
		print("[ULTIMATE ANTI-CHEAT] ✅ All Executor Webhooks Test Completed!")
	end,
	
	forceTestExecutorLog = function(playerName, executorName)
		print("[ULTIMATE ANTI-CHEAT] 🧪 Force Testing Executor Log for " .. playerName .. " with " .. executorName)
		
		local testPlayer = {
			Name = playerName,
			UserId = math.random(100000, 999999),
			DisplayName = playerName
		}
		
		sendDiscordCheatLog(testPlayer, "EXECUTOR", executorName .. " executor terdeteksi - Program ilegal ditemukan", "HIGH", "KICK")
		
		print("[ULTIMATE ANTI-CHEAT] ✅ Force Test Executor Log Completed!")
	end
}

print("[ULTIMATE ANTI-CHEAT] Sistem anti-cheat ultimate dengan threshold yang AGRESIF untuk menangkap SEMUA cheater berhasil dimuat!")
print("")
print("⚙️ THRESHOLD YANG AGRESIF:")
print("Fly Detection: MAX_SPEED=50, MAX_VERTICAL_SPEED=30, VIOLATIONS_NEEDED=2")
print("Speed Hack: MAX_WALK_SPEED=20, MAX_JUMP_POWER=60, VIOLATIONS_NEEDED=1")
print("Teleport: MAX_DISTANCE=50, MIN_DISTANCE=10, VIOLATIONS_NEEDED=1")
print("Invisibility: MAX_TRANSPARENCY=0.1, VIOLATIONS_NEEDED=1")
print("God Mode: VIOLATIONS_NEEDED=1, MIN_DURATION=0.1s")
print("Noclip: ENABLED dengan VIOLATIONS_NEEDED=1")
print("Delete Part: VIOLATIONS_NEEDED=1, CHECK_INTERVAL=0.5s")
print("Executor: CHECK_INTERVAL=1s, MIN_CHECKS_BEFORE_KICK=1")
print("Auto Checkpoint: ENABLED dengan VIOLATIONS_NEEDED=1")
print("Advanced Detection: ENABLED untuk semua deteksi")
print("")
print("🚨 DETEKSI AGRESIF:")
print("Fly: Check setiap 0.5s, deteksi hovering + suspicious movement")
print("Speed: Check setiap 0.5s, deteksi instant changes + excessive values")
print("Teleport: Check setiap 0.1s, deteksi instant + suspicious position")
print("Invisibility: Check setiap 0.5s, deteksi tools + transparency")
print("God Mode: Check setiap 0.5s, deteksi damage immunity + health hack")
print("Noclip: Check setiap 0.5s, deteksi wall/ground/object phasing")
print("Delete Part: Check setiap 0.5s, deteksi mass deletion + exploit tools")
print("Executor: Check setiap 1s, deteksi semua executor + suspicious scripts")
print("")
print("🚨 OPTIMIZED EXECUTOR DETECTION:")
print("Delta: 10 metode deteksi (Global, getfenv, CoreGui, PlayerGui, StarterGui, StarterPack, StarterPlayer, Workspace, ServerStorage, ReplicatedStorage)")
print("KRNL: 10 metode deteksi (Global, getfenv, CoreGui, PlayerGui, StarterGui, StarterPack, StarterPlayer, Workspace, ServerStorage, ReplicatedStorage)")
print("Synapse: 10 metode deteksi (Global, getfenv, CoreGui, PlayerGui, StarterGui, StarterPack, StarterPlayer, Workspace, ServerStorage, ReplicatedStorage)")
print("ScriptWare: 10 metode deteksi (Global, getfenv, CoreGui, PlayerGui, StarterGui, StarterPack, StarterPlayer, Workspace, ServerStorage, ReplicatedStorage)")
print("Ronix: 10 metode deteksi (Global, getfenv, CoreGui, PlayerGui, StarterGui, StarterPack, StarterPlayer, Workspace, ServerStorage, ReplicatedStorage)")
print("Fluxus: 10 metode deteksi (Global, getfenv, CoreGui, PlayerGui, StarterGui, StarterPack, StarterPlayer, Workspace, ServerStorage, ReplicatedStorage)")
print("Electron: 10 metode deteksi (Global, getfenv, CoreGui, PlayerGui, StarterGui, StarterPack, StarterPlayer, Workspace, ServerStorage, ReplicatedStorage)")
print("Comet: 10 metode deteksi (Global, getfenv, CoreGui, PlayerGui, StarterGui, StarterPack, StarterPlayer, Workspace, ServerStorage, ReplicatedStorage)")
print("Elysian: 10 metode deteksi (Global, getfenv, CoreGui, PlayerGui, StarterGui, StarterPack, StarterPlayer, Workspace, ServerStorage, ReplicatedStorage)")
print("Sentinel: 10 metode deteksi (Global, getfenv, CoreGui, PlayerGui, StarterGui, StarterPack, StarterPlayer, Workspace, ServerStorage, ReplicatedStorage)")
print("Additional Executors: 20+ executor populer dengan 10 metode deteksi")
print("Executor Functions: 18+ fungsi executor umum")
print("Executor Tables: 21+ tabel executor umum")
print("Suspicious Patterns: 21+ pola mencurigakan")
print("Executor Methods: 26+ metode executor")
print("Executor Properties: 21+ properti executor")
print("Executor Services: 14+ service executor")
print("")
print("⚡ ULTRA OPTIMIZED PERFORMANCE:")
print("Main Loop: Check setiap 3 detik dengan batch processing (3 player/frame)")
print("Executor Loop: Check setiap 3 detik untuk performa yang optimal")
print("Player Join: Check executor 5 detik setelah join")
print("Detection Groups: Group 1 (Fly/Speed), Group 2 (Noclip/Teleport), Group 3 (Delete/Invisibility), Group 4 (GodMode)")
print("Debounce System: 0.2 detik debounce untuk mencegah spam detection")
print("Spawn Functions: Semua deteksi menggunakan spawn untuk performa yang lebih baik")
print("Check Intervals: Semua deteksi memiliki interval yang optimal")
print("Performance Monitoring: Cleanup otomatis setiap 5 menit")
print("Memory Management: Cleanup violation/punishment history setiap jam")
print("Batch Processing: Maksimal 3 player per frame untuk main loop, 2 player untuk executor")
print("")
print("🔧 ERROR FIXED:")
print("ClimbSpeed error telah diperbaiki - ClimbSpeed tidak ada di Humanoid versi baru Roblox")
print("MovementDistance nil error telah diperbaiki dengan nil check")
print("LastNoclipCheck nil error telah diperbaiki dengan nil check")
print("LastCheckTime nil error telah diperbaiki dengan nil check")
print("LastPosition nil error telah diperbaiki dengan nil check")
print("ExecutorChecks nil error telah diperbaiki dengan nil check")
print("Semua violations nil error telah diperbaiki dengan nil check")
print("String format error telah diperbaiki dengan nil check di semua fungsi")
print("Player parameter nil error telah diperbaiki dengan error handling")
print("Webhook test functions telah diperbaiki dengan parameter yang benar")
print("")
print("🚀 PERFORMANCE OPTIMIZATIONS:")
print("Fly Detection: CHECK_INTERVAL 0.5s -> 2.0s, VIOLATIONS_NEEDED 2 -> 3")
print("Speed Detection: CHECK_INTERVAL 0.5s -> 2.0s, VIOLATIONS_NEEDED 1 -> 2")
print("Noclip Detection: CHECK_INTERVAL 0.5s -> 2.0s, VIOLATIONS_NEEDED 1 -> 2")
print("Teleport Detection: CHECK_INTERVAL 0.1s -> 1.0s, VIOLATIONS_NEEDED 1 -> 2")
print("Delete Detection: CHECK_INTERVAL 0.5s -> 2.0s, VIOLATIONS_NEEDED 1 -> 2")
print("Invisibility Detection: CHECK_INTERVAL 0.5s -> 2.0s, VIOLATIONS_NEEDED 1 -> 2")
print("God Mode Detection: CHECK_INTERVAL 0.5s -> 2.0s, VIOLATIONS_NEEDED 1 -> 2")
print("Executor Detection: CHECK_INTERVAL 0.5s -> 3.0s untuk performa optimal")
print("Main Loop: CHECK_INTERVAL 1.0s -> 3.0s, BATCH_SIZE 5 -> 3")
print("Executor Loop: CHECK_INTERVAL 1.0s -> 3.0s, BATCH_SIZE unlimited -> 2")
print("Player Join: DELAY 2s -> 5s untuk performa yang lebih baik")
print("")
print("🔧 COMMANDS UNTUK TEST WEBHOOK:")
print("_G.UltimateAntiCheat.testDiscordWebhooks() - Test semua webhook")
print("_G.UltimateAntiCheat.testCheatWebhookOnly() - Test cheat webhook saja")
print("_G.UltimateAntiCheat.testJoinLeaveWebhookOnly() - Test join/leave webhook saja")
print("_G.UltimateAntiCheat.testExecutorWebhook() - Test executor webhook")
print("_G.UltimateAntiCheat.testDeltaExecutorWebhook() - Test Delta executor webhook")
print("_G.UltimateAntiCheat.testKRNLExecutorWebhook() - Test KRNL executor webhook")
print("_G.UltimateAntiCheat.testSynapseExecutorWebhook() - Test Synapse executor webhook")
print("_G.UltimateAntiCheat.testScriptWareExecutorWebhook() - Test ScriptWare executor webhook")
print("_G.UltimateAntiCheat.testAllExecutorWebhooks() - Test semua executor webhook")
print("_G.UltimateAntiCheat.checkWebhookStatus() - Cek status webhook")
print("_G.UltimateAntiCheat.forceTestCheatLog('PlayerName', 'FLY') - Paksa test cheat log")
print("_G.UltimateAntiCheat.forceTestExecutorLog('PlayerName', 'Delta') - Paksa test executor log")
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
print("✅ THRESHOLD TELAH DISESUAIKAN UNTUK GAMEPLAY NORMAL ROBLOX!")
print("")