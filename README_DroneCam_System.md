# 🚁 DroneCam Freecam System untuk Roblox

## 📋 Deskripsi
DroneCam Freecam System adalah script Roblox yang memungkinkan pemain untuk menggunakan kamera drone yang dapat dikontrol dengan chat commands. Sistem ini memiliki batasan jarak 30 studs dari spawn point dan menyembunyikan semua UI saat drone aktif.

## ✨ Fitur Utama

### 🎮 Kontrol Drone
- **Chat Commands**: Aktifkan dengan `/drone`, `/dronecam`, atau `/dronecamera`
- **Deactivate Commands**: Matikan dengan `/offdrone`, `/offdronecam`, atau `/offdronecamera`
- **WASD Movement**: Gerakan drone dengan tombol WASD
- **Space/Shift**: Naik/turun dengan Space dan Left Shift
- **Mouse Look**: Lihat sekitar dengan Left Ctrl + Mouse

### 🚧 Batasan dan Keamanan
- **Jarak Maksimal**: 30 studs dari spawn point
- **Tinggi Maksimal**: 100 studs dari ground
- **Tinggi Minimal**: -50 studs dari ground
- **UI Hidden**: Semua UI disembunyikan saat drone aktif
- **Debounce**: Mencegah spam commands

### 🎨 UI dan Visual
- **Info Panel**: Menampilkan status, jarak, dan instruksi
- **Distance Warning**: Peringatan saat mencapai jarak maksimal
- **Smooth Movement**: Gerakan kamera yang halus
- **Visual Feedback**: Warna yang berubah berdasarkan jarak

## 📁 File yang Tersedia

### 1. `DroneCam_Freecam_System.lua`
Script dasar dengan fitur lengkap:
- Chat commands untuk activate/deactivate
- Batasan jarak 30 studs
- Hide semua UI saat drone aktif
- Smooth camera movement
- Distance display dengan warning
- Debounce untuk mencegah spam

### 2. `Advanced_DroneCam_System.lua`
Script advanced dengan fitur tambahan:
- Speed control dengan 3 mode (Slow/Normal/Fast)
- Height display dan limit
- Speed display dengan visual feedback
- Speed buttons untuk kontrol cepat
- Chat commands untuk speed control

### 3. `Install_DroneCam_System.lua`
Script instalasi otomatis:
- Setup system folder
- Create remote events
- Server logging
- Installation verification
- Player notifications

## 🔧 Cara Instalasi

### Metode 1: ServerScriptService (Recommended)
1. **Buka Roblox Studio**
2. **Pergi ke ServerScriptService**
3. **Insert Object → Script**
4. **Copy paste script `DroneCam_Freecam_System.lua`**
5. **Save dan Publish**

### Metode 2: StarterGui
1. **Buka Roblox Studio**
2. **Pergi ke StarterGui**
3. **Insert Object → ScreenGui**
4. **Insert Object → LocalScript**
5. **Copy paste script**
6. **Save dan Publish**

### Metode 3: Installer Script
1. **Buka Roblox Studio**
2. **Pergi ke ServerScriptService**
3. **Insert Object → Script**
4. **Copy paste script `Install_DroneCam_System.lua`**
5. **Save dan Publish**
6. **Script akan otomatis setup system**

## 💬 Chat Commands

### Aktifkan Drone
```
/drone
/dronecam
/dronecamera
```

### Matikan Drone
```
/offdrone
/offdronecam
/offdronecamera
```

### Speed Control (Advanced Version)
```
/dronefast    - Set speed to fast (100)
/droneslow    - Set speed to slow (25)
/dronenormal  - Set speed to normal (50)
```

## 🎮 Kontrol

### Gerakan
- **W** - Maju
- **S** - Mundur
- **A** - Kiri
- **D** - Kanan
- **Space** - Naik
- **Left Shift** - Turun

### Kamera
- **Left Ctrl + Mouse** - Lihat sekitar
- **Mouse Movement** - Rotasi kamera

## ⚙️ Konfigurasi

### Basic Configuration
```lua
local CONFIG = {
    DRONE_SPEED = 50,           -- Kecepatan drone
    DRONE_MAX_DISTANCE = 30,    -- Batasan jarak maksimal
    DRONE_HEIGHT_LIMIT = 100,   -- Batasan tinggi maksimal
    DRONE_MIN_HEIGHT = -50,     -- Batasan tinggi minimal
    CAMERA_SENSITIVITY = 0.5,   -- Sensitivitas kamera
    DEBOUNCE_TIME = 0.5,        -- Debounce untuk command
}
```

### Advanced Configuration
```lua
local CONFIG = {
    DRONE_SPEED = 50,           -- Kecepatan drone default
    DRONE_SPEED_FAST = 100,     -- Kecepatan drone cepat
    DRONE_SPEED_SLOW = 25,      -- Kecepatan drone lambat
    DRONE_MAX_DISTANCE = 30,    -- Batasan jarak maksimal
    DRONE_HEIGHT_LIMIT = 100,   -- Batasan tinggi maksimal
    DRONE_MIN_HEIGHT = -50,     -- Batasan tinggi minimal
    CAMERA_SENSITIVITY = 0.5,   -- Sensitivitas kamera
    CAMERA_FOV = 70,            -- Field of view
    DEBOUNCE_TIME = 0.5,        -- Debounce untuk command
    MOVEMENT_DEBOUNCE = 0.01,   -- Debounce untuk movement
}
```

## 🔧 Global Functions

### Basic Functions
```lua
_G.DroneCamSystem.activate()        -- Activate drone
_G.DroneCamSystem.deactivate()     -- Deactivate drone
_G.DroneCamSystem.toggle()         -- Toggle drone
_G.DroneCamSystem.isActive()       -- Check if drone is active
_G.DroneCamSystem.getDistance()    -- Get current distance
_G.DroneCamSystem.getMaxDistance() -- Get max distance
```

### Advanced Functions
```lua
_G.AdvancedDroneCamSystem.activate()        -- Activate drone
_G.AdvancedDroneCamSystem.deactivate()     -- Deactivate drone
_G.AdvancedDroneCamSystem.toggle()         -- Toggle drone
_G.AdvancedDroneCamSystem.isActive()       -- Check if drone is active
_G.AdvancedDroneCamSystem.getDistance()     -- Get current distance
_G.AdvancedDroneCamSystem.getMaxDistance() -- Get max distance
_G.AdvancedDroneCamSystem.getSpeed()       -- Get current speed
_G.AdvancedDroneCamSystem.getSpeedMode()   -- Get speed mode
_G.AdvancedDroneCamSystem.setSpeed(100, 'Fast') -- Set speed
```

### Installer Functions
```lua
_G.DroneCamInstaller.getConfig()           -- Get installation config
_G.DroneCamInstaller.reinstall()           -- Reinstall system
_G.DroneCamInstaller.checkInstallation()   -- Check installation status
_G.DroneCamInstaller.getSystemInfo()       -- Get system info
```

## 🛠️ Troubleshooting

### ❌ Drone Tidak Aktif
```
✅ Check apakah script berjalan di client
✅ Pastikan chat commands diketik dengan benar
✅ Check console untuk error messages
✅ Pastikan debounce tidak menghalangi
```

### ❌ Drone Tidak Bergerak
```
✅ Check apakah WASD keys berfungsi
✅ Pastikan drone aktif
✅ Check movement debounce
✅ Pastikan input handling berfungsi
```

### ❌ UI Tidak Hidden
```
✅ Check hideAllUI() function
✅ Pastikan hiddenGuis table berfungsi
✅ Check StarterGui:SetCoreGuiEnabled()
✅ Pastikan UI elements terdeteksi
```

### ❌ Jarak Tidak Terbatas
```
✅ Check clampPosition() function
✅ Pastikan spawnPosition ter-set
✅ Check getDistanceFromSpawn() function
✅ Pastikan CONFIG.DRONE_MAX_DISTANCE benar
```

## 📊 Performance

### CPU Usage
- **Minimal**: Hanya saat drone aktif
- **Optimized**: Debounce dan efficient loops
- **Lightweight**: Tidak ada unnecessary calculations

### Memory Usage
- **Low**: Minimal memory footprint
- **Cleanup**: Proper cleanup saat deactivate
- **Efficient**: No memory leaks

### Network Impact
- **None**: Pure client-side system
- **No Lag**: Tidak ada network calls
- **Smooth**: 60fps performance

## 🔒 Keamanan

### Client-Side Only
- **No Server Access**: Tidak bisa akses server
- **Local Only**: Hanya bekerja di client
- **Safe**: Tidak bisa exploit server

### Distance Limitation
- **30 Studs Max**: Batasan jarak yang ketat
- **Spawn Point**: Berdasarkan spawn point player
- **Real-time Check**: Check jarak setiap frame

### UI Protection
- **Hide All UI**: Semua UI disembunyikan
- **Core GUI**: Core GUI juga disembunyikan
- **Restore**: UI dikembalikan saat deactivate

## 🎯 Use Cases

### 1. Exploration
- **Map Exploration**: Explore map dengan drone
- **Building Inspection**: Inspect buildings dari atas
- **Scenic Views**: Ambil screenshot dari angle yang bagus

### 2. Game Development
- **Level Design**: Test level design dari berbagai angle
- **Camera Testing**: Test camera angles untuk cutscenes
- **Debugging**: Debug map issues dari atas

### 3. Content Creation
- **Screenshots**: Ambil screenshot yang bagus
- **Videos**: Record gameplay dari angle yang unik
- **Streaming**: Stream dengan camera angle yang menarik

## 📈 Updates dan Versions

### Version 1.0.0
- Basic drone cam functionality
- Chat commands
- Distance limitation
- UI hiding
- Smooth movement

### Version 1.1.0 (Advanced)
- Speed control
- Height display
- Visual feedback
- Speed buttons
- Enhanced UI

### Version 1.2.0 (Installer)
- Automatic installation
- Server logging
- Installation verification
- Player notifications

## 🤝 Support

### Getting Help
- **Check Console**: Lihat console untuk error messages
- **Test Commands**: Test dengan global functions
- **Check Configuration**: Pastikan config benar
- **Verify Installation**: Pastikan script ter-install dengan benar

### Reporting Issues
- **Describe Problem**: Jelaskan masalah yang terjadi
- **Include Console**: Include console output
- **Steps to Reproduce**: Langkah-langkah untuk reproduce
- **Expected Behavior**: Behavior yang diharapkan

## 📝 License

Script ini dibuat untuk penggunaan pribadi dan edukasi. Silakan gunakan dengan bijak dan sesuai dengan Terms of Service Roblox.

## 🎉 Credits

- **Developer**: DroneCam Team
- **Version**: 1.2.0
- **Last Updated**: 2024
- **Compatibility**: Roblox Studio & Game

---

**🚁 Enjoy your DroneCam Freecam System!**