# 🔧 Troubleshooting DroneCam System

## ❌ Masalah: Chat Commands Tidak Berfungsi

### 🔍 Diagnosa Masalah

#### 1. **Check Script Location**
```
✅ ServerScriptService: Script berjalan di server
❌ StarterGui: Script berjalan di client (CORRECT)
✅ LocalScript: Script berjalan di client (CORRECT)
```

#### 2. **Check Console Output**
```
✅ Script loaded: "🚁 DRONE CAM SYSTEM READY!"
✅ Chat received: "💬 Chat: /drone"
✅ Command detected: "✅ Activating drone..."
❌ No output: Script tidak berjalan
```

#### 3. **Check Player Connection**
```
✅ LOCAL_PLAYER: Players.LocalPlayer
✅ PlayerGui: LOCAL_PLAYER:WaitForChild("PlayerGui")
✅ Character: LOCAL_PLAYER.Character
❌ Nil values: Player belum load
```

---

### 🛠️ Solusi Masalah

#### **Solusi 1: Gunakan Script yang Fixed**
```lua
-- Gunakan Fixed_DroneCam_System.lua
-- Multiple chat handling methods
-- Debug messages untuk troubleshooting
-- Reduced debounce time
```

#### **Solusi 2: Gunakan Script yang Simple**
```lua
-- Gunakan Simple_DroneCam_System.lua
-- Simple dan reliable
-- Chat commands pasti berfungsi
-- Minimal complexity
```

#### **Solusi 3: Gunakan Debug System**
```lua
-- Gunakan DroneCam_Debug_System.lua
-- Real-time chat monitoring
-- Command detection testing
-- Easy troubleshooting
```

---

### 🔧 Langkah-langkah Troubleshooting

#### **Step 1: Install Debug System**
1. **Buka Roblox Studio**
2. **Pergi ke StarterGui**
3. **Insert Object → ScreenGui**
4. **Insert Object → LocalScript**
5. **Copy paste script `DroneCam_Debug_System.lua`**
6. **Save dan Publish**

#### **Step 2: Test Chat System**
1. **Join game**
2. **Click 'Debug ON' button**
3. **Type `/debug` di chat**
4. **Check debug panel untuk output**

#### **Step 3: Test Drone Commands**
1. **Type `/drone` di chat**
2. **Check debug panel untuk "DRONE COMMAND DETECTED!"**
3. **Type `/offdrone` di chat**
4. **Check debug panel untuk "OFF DRONE COMMAND DETECTED!"**

#### **Step 4: Check Console**
1. **Open Developer Console (F9)**
2. **Look for error messages**
3. **Check script output**
4. **Verify services are loaded**

---

### 🐛 Common Issues & Solutions

#### **Issue 1: Script Tidak Berjalan**
```
❌ Problem: No console output
✅ Solution: 
- Check script location (must be in StarterGui)
- Check script type (must be LocalScript)
- Check for syntax errors
- Verify script is enabled
```

#### **Issue 2: Chat Tidak Terdeteksi**
```
❌ Problem: Chat messages not detected
✅ Solution:
- Use Fixed_DroneCam_System.lua
- Check LOCAL_PLAYER.Chatted connection
- Verify player is local player
- Check debounce timing
```

#### **Issue 3: Drone Tidak Aktif**
```
❌ Problem: Drone not activating
✅ Solution:
- Check activateDrone() function
- Verify camera creation
- Check UI hiding
- Verify droneActive flag
```

#### **Issue 4: Drone Tidak Bergerak**
```
❌ Problem: Drone not moving
✅ Solution:
- Check handleInput() function
- Verify UserInputService
- Check movement debounce
- Verify droneCFrame
```

#### **Issue 5: UI Tidak Hidden**
```
❌ Problem: UI not hiding
✅ Solution:
- Check hideAllUI() function
- Verify hiddenGuis table
- Check StarterGui:SetCoreGuiEnabled()
- Verify UI elements detection
```

#### **Issue 6: Jarak Tidak Terbatas**
```
❌ Problem: Distance not limited
✅ Solution:
- Check clampPosition() function
- Verify spawnPosition
- Check getDistanceFromSpawn()
- Verify CONFIG.DRONE_MAX_DISTANCE
```

---

### 🔍 Debug Commands

#### **Global Functions untuk Testing**
```lua
-- Test activation
_G.SimpleDroneCamSystem.activate()

-- Test deactivation
_G.SimpleDroneCamSystem.deactivate()

-- Test toggle
_G.SimpleDroneCamSystem.toggle()

-- Check status
_G.SimpleDroneCamSystem.isActive()

-- Get distance
_G.SimpleDroneCamSystem.getDistance()

-- Test chat
_G.SimpleDroneCamSystem.testChat()
```

#### **Debug System Commands**
```lua
-- Toggle debug panel
_G.DroneCamDebugSystem.toggle()

-- Test chat system
_G.DroneCamDebugSystem.testChat()

-- Test player info
_G.DroneCamDebugSystem.testPlayer()

-- Test services
_G.DroneCamDebugSystem.testServices()

-- Add log message
_G.DroneCamDebugSystem.addLog("Test message")

-- Update status
_G.DroneCamDebugSystem.updateStatus("Testing")

-- Get logs
_G.DroneCamDebugSystem.getLogs()

-- Clear logs
_G.DroneCamDebugSystem.clearLogs()

-- Check if debug is active
_G.DroneCamDebugSystem.isActive()
```

---

### 📋 Checklist Troubleshooting

#### **Pre-Installation**
- [ ] Script is LocalScript (not Script)
- [ ] Script is in StarterGui (not ServerScriptService)
- [ ] Script is enabled
- [ ] No syntax errors

#### **Installation**
- [ ] Script loads without errors
- [ ] Console shows "READY!" message
- [ ] Global functions available
- [ ] No runtime errors

#### **Chat Testing**
- [ ] Debug system shows chat messages
- [ ] Commands are detected
- [ ] Debounce is working
- [ ] No command conflicts

#### **Drone Testing**
- [ ] Drone activates successfully
- [ ] Camera switches properly
- [ ] UI hides correctly
- [ ] Movement works
- [ ] Distance limit works
- [ ] Deactivation works

---

### 🚨 Emergency Fixes

#### **Fix 1: Reset Script**
```lua
-- Clear all global variables
_G.SimpleDroneCamSystem = nil
_G.FixedDroneCamSystem = nil
_G.DroneCamDebugSystem = nil

-- Restart script
-- Reinstall from scratch
```

#### **Fix 2: Manual Activation**
```lua
-- Manual drone activation
_G.SimpleDroneCamSystem.activate()

-- Manual drone deactivation
_G.SimpleDroneCamSystem.deactivate()
```

#### **Fix 3: Debug Mode**
```lua
-- Enable debug mode
_G.DroneCamDebugSystem.toggle()

-- Test all systems
_G.DroneCamDebugSystem.testChat()
_G.DroneCamDebugSystem.testPlayer()
_G.DroneCamDebugSystem.testServices()
```

---

### 📞 Support

#### **Getting Help**
1. **Check Console**: Look for error messages
2. **Use Debug System**: Monitor chat and commands
3. **Test Global Functions**: Verify script functionality
4. **Check Configuration**: Verify settings

#### **Reporting Issues**
1. **Describe Problem**: What exactly is happening?
2. **Include Console**: Copy console output
3. **Steps to Reproduce**: How to recreate the issue
4. **Expected Behavior**: What should happen?
5. **Script Version**: Which script are you using?

#### **Quick Fixes**
- **Script not working**: Use Simple_DroneCam_System.lua
- **Chat not detected**: Use Fixed_DroneCam_System.lua
- **Need debugging**: Use DroneCam_Debug_System.lua
- **Still not working**: Check script location and type

---

### ✅ Success Indicators

#### **Script Working**
- Console shows "READY!" message
- Global functions available
- No runtime errors
- Debug system responsive

#### **Chat Working**
- Debug panel shows chat messages
- Commands detected properly
- Debounce working correctly
- No command conflicts

#### **Drone Working**
- Drone activates on command
- Camera switches smoothly
- UI hides completely
- Movement responsive
- Distance limit enforced
- Deactivation works

---

**🔧 Happy Troubleshooting!**