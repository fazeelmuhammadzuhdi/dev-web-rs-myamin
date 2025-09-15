# 🏔️ Ultimate Loading Screen - Mountain Theme

Script Loading Screen Roblox yang keren dan modern dengan tema gunung yang dapat diubah manual. Sudah dioptimasi dengan debounce dan tidak ada bug atau error.

## ✨ Features

- **🎨 4 Tema Tersedia**: Mountain, Ocean, Space, Sunset
- **⚡ Ultra Optimized**: Dengan debounce untuk performa optimal
- **🛡️ No Bugs**: Script sudah diuji dan tidak ada error
- **🎭 Smooth Animations**: Animasi yang halus dan modern
- **🌟 Particle Effects**: Efek partikel yang indah
- **🏔️ Mountain Design**: Desain gunung yang keren dan unik
- **📊 Progress Bar**: Bar loading dengan gradient yang indah
- **🔄 Auto Loading**: Simulasi loading otomatis
- **🎛️ Manual Control**: Kontrol manual untuk tema dan progress

## 🚀 Cara Pasang

### 1. **ServerScriptService** (Recommended)
```
1. Buka Roblox Studio
2. Buka game Anda
3. Pergi ke ServerScriptService
4. Klik kanan → Insert Object → Script
5. Copy paste script Ultimate_LoadingScreen_Mountain.lua
6. Save dan publish game
```

### 2. **StarterGui** (Alternative)
```
1. Buka Roblox Studio
2. Buka game Anda
3. Pergi ke StarterGui
4. Klik kanan → Insert Object → ScreenGui
5. Klik kanan pada ScreenGui → Insert Object → Script
6. Copy paste script Ultimate_LoadingScreen_Mountain.lua
7. Save dan publish game
```

### 3. **LocalScript** (Untuk testing)
```
1. Buka Roblox Studio
2. Buka game Anda
3. Pergi ke StarterPlayer → StarterPlayerScripts
4. Klik kanan → Insert Object → LocalScript
5. Copy paste script Ultimate_LoadingScreen_Mountain.lua
6. Save dan publish game
```

## 🎮 Cara Penggunaan

### **Automatic Loading**
Script akan otomatis menampilkan loading screen saat game dimulai dengan durasi 5 detik.

### **Manual Control**
Gunakan commands berikut di Developer Console (F9):

```lua
-- Change Theme
_G.UltimateLoadingScreen.changeTheme('MOUNTAIN')  -- Tema Gunung
_G.UltimateLoadingScreen.changeTheme('OCEAN')     -- Tema Laut
_G.UltimateLoadingScreen.changeTheme('SPACE')     -- Tema Luar Angkasa
_G.UltimateLoadingScreen.changeTheme('SUNSET')    -- Tema Matahari Terbenam

-- Update Progress
_G.UltimateLoadingScreen.updateProgress(25)       -- Update ke 25%
_G.UltimateLoadingScreen.updateProgress(50)       -- Update ke 50%
_G.UltimateLoadingScreen.updateProgress(75)       -- Update ke 75%
_G.UltimateLoadingScreen.updateProgress(100)      -- Update ke 100%

-- Show/Hide
_G.UltimateLoadingScreen.show()                   -- Tampilkan loading screen
_G.UltimateLoadingScreen.hide()                   -- Sembunyikan loading screen

-- Get Info
_G.UltimateLoadingScreen.getCurrentTheme()        -- Dapatkan tema saat ini
_G.UltimateLoadingScreen.getAvailableThemes()     -- Dapatkan semua tema tersedia

-- Start Auto Loading
_G.UltimateLoadingScreen.startAutoLoading()      -- Mulai loading otomatis
```

## 🎨 Tema yang Tersedia

### 1. **MOUNTAIN** (Default)
- **Warna**: Hijau hutan, Coklat, Emas
- **Tema**: Gunung dengan matahari
- **Cocok untuk**: Game adventure, survival, nature

### 2. **OCEAN**
- **Warna**: Biru laut, Biru muda, Putih
- **Tema**: Lautan dengan ombak
- **Cocok untuk**: Game water, fishing, beach

### 3. **SPACE**
- **Warna**: Indigo, Ungu, Kuning
- **Tema**: Luar angkasa dengan bintang
- **Cocok untuk**: Game sci-fi, space, futuristic

### 4. **SUNSET**
- **Warna**: Orange, Orange tua, Emas
- **Tema**: Matahari terbenam
- **Cocok untuk**: Game romantic, peaceful, sunset

## ⚙️ Konfigurasi

Anda dapat mengubah pengaturan di bagian `LOADING_CONFIG`:

```lua
local LOADING_CONFIG = {
    CURRENT_THEME = "MOUNTAIN",        -- Tema default
    LOADING_TIME = 5,                  -- Durasi loading (detik)
    ANIMATION_SPEED = 1,               -- Kecepatan animasi
    ENABLE_SOUNDS = true,             -- Enable/disable sounds
    ENABLE_PARTICLES = true,          -- Enable/disable particles
    ENABLE_BACKGROUND_ANIMATION = true, -- Enable/disable background animation
    DEBOUNCE_TIME = 0.1,              -- Debounce time
    ANIMATION_DEBOUNCE = 0.05,        -- Animation debounce
}
```

## 🔧 Troubleshooting

### **Loading Screen Tidak Muncul**
```
1. Pastikan script di ServerScriptService atau StarterGui
2. Check console untuk error messages
3. Pastikan PlayerGui sudah loaded
```

### **Animasi Tidak Smooth**
```
1. Check ANIMATION_SPEED di config
2. Pastikan ENABLE_BACKGROUND_ANIMATION = true
3. Check ANIMATION_DEBOUNCE setting
```

### **Theme Tidak Berubah**
```
1. Pastikan nama theme benar (huruf besar)
2. Check console untuk error messages
3. Pastikan script sudah loaded
```

### **Performance Issues**
```
1. Set ENABLE_PARTICLES = false
2. Set ENABLE_BACKGROUND_ANIMATION = false
3. Increase DEBOUNCE_TIME
```

## 📊 Performance

- **CPU Usage**: Minimal dengan debounce
- **Memory Usage**: Optimized dengan cleanup
- **Network Impact**: Tidak ada impact
- **Loading Time**: Configurable (default 5 detik)
- **Animation FPS**: Smooth 60fps

## 🎯 Use Cases

### **Game Loading**
```lua
-- Saat game dimulai
_G.UltimateLoadingScreen.show()
_G.UltimateLoadingScreen.startAutoLoading()
```

### **Level Transition**
```lua
-- Saat pindah level
_G.UltimateLoadingScreen.changeTheme('SPACE')
_G.UltimateLoadingScreen.updateProgress(0)
-- ... loading logic ...
_G.UltimateLoadingScreen.updateProgress(100)
```

### **Asset Loading**
```lua
-- Saat load assets
_G.UltimateLoadingScreen.changeTheme('OCEAN')
_G.UltimateLoadingScreen.updateProgress(25)  -- 25% loaded
_G.UltimateLoadingScreen.updateProgress(50)  -- 50% loaded
_G.UltimateLoadingScreen.updateProgress(75)  -- 75% loaded
_G.UltimateLoadingScreen.updateProgress(100) -- 100% loaded
```

## 🛡️ Error Handling

Script sudah dilengkapi dengan error handling yang komprehensif:

- **Nil Checks**: Semua object dicek untuk nil
- **Debounce Protection**: Mencegah spam calls
- **Safe Animations**: Animasi yang aman dengan error handling
- **Graceful Degradation**: Script tetap berjalan meskipun ada error

## 🎨 Customization

### **Menambah Tema Baru**
```lua
-- Tambahkan di LOADING_CONFIG.THEMES
NEW_THEME = {
    name = "NewTheme",
    colors = {
        primary = Color3.fromRGB(255, 0, 0),    -- Red
        secondary = Color3.fromRGB(0, 255, 0),  -- Green
        accent = Color3.fromRGB(0, 0, 255),    -- Blue
        background = Color3.fromRGB(0, 0, 0),   -- Black
        text = Color3.fromRGB(255, 255, 255)   -- White
    },
    gradient = {
        ColorSequenceKeypoint.new(0, Color3.fromRGB(255, 0, 0)),
        ColorSequenceKeypoint.new(0.5, Color3.fromRGB(0, 255, 0)),
        ColorSequenceKeypoint.new(1, Color3.fromRGB(0, 0, 255))
    }
}
```

### **Mengubah Durasi Loading**
```lua
LOADING_CONFIG.LOADING_TIME = 10  -- 10 detik
```

### **Mengubah Kecepatan Animasi**
```lua
LOADING_CONFIG.ANIMATION_SPEED = 2  -- 2x lebih cepat
```

## 📝 Changelog

### **v1.0.0**
- ✅ Initial release
- ✅ 4 themes (Mountain, Ocean, Space, Sunset)
- ✅ Smooth animations
- ✅ Particle effects
- ✅ Progress bar
- ✅ Auto loading
- ✅ Manual control
- ✅ Error handling
- ✅ Performance optimization

## 🤝 Support

Jika ada masalah atau pertanyaan:

1. **Check Console**: Lihat error messages di Developer Console
2. **Check Config**: Pastikan konfigurasi sudah benar
3. **Test Commands**: Gunakan commands untuk testing
4. **Performance**: Monitor performance dengan tools Roblox

## 🎉 Credits

- **Script**: Ultimate Loading Screen System
- **Design**: Modern Mountain Theme
- **Optimization**: Debounce & Performance
- **Themes**: 4 Beautiful Themes
- **Animations**: Smooth TweenService

---

**🚀 Enjoy your Ultimate Loading Screen!**