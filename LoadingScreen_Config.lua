-- Loading Screen Configuration
-- File konfigurasi untuk Ultimate Loading Screen
-- Mudah diubah dan dikustomisasi

local LoadingScreenConfig = {}

-- Main Configuration
LoadingScreenConfig.MAIN = {
	-- Default theme
	DEFAULT_THEME = "MOUNTAIN",
	
	-- Loading duration
	LOADING_TIME = 5, -- seconds
	
	-- Animation speed
	ANIMATION_SPEED = 1, -- multiplier
	
	-- Enable/disable features
	ENABLE_SOUNDS = true,
	ENABLE_PARTICLES = true,
	ENABLE_BACKGROUND_ANIMATION = true,
	
	-- Debounce settings
	DEBOUNCE_TIME = 0.1, -- seconds
	ANIMATION_DEBOUNCE = 0.05, -- seconds
}

-- Theme Configuration
LoadingScreenConfig.THEMES = {
	MOUNTAIN = {
		name = "Mountain",
		colors = {
			primary = Color3.fromRGB(34, 139, 34), -- Forest Green
			secondary = Color3.fromRGB(139, 69, 19), -- Saddle Brown
			accent = Color3.fromRGB(255, 215, 0), -- Gold
			background = Color3.fromRGB(25, 25, 25), -- Dark Gray
			text = Color3.fromRGB(255, 255, 255) -- White
		},
		gradient = {
			ColorSequenceKeypoint.new(0, Color3.fromRGB(34, 139, 34)),
			ColorSequenceKeypoint.new(0.5, Color3.fromRGB(139, 69, 19)),
			ColorSequenceKeypoint.new(1, Color3.fromRGB(255, 215, 0))
		}
	},
	
	OCEAN = {
		name = "Ocean",
		colors = {
			primary = Color3.fromRGB(0, 100, 200), -- Ocean Blue
			secondary = Color3.fromRGB(0, 150, 255), -- Light Blue
			accent = Color3.fromRGB(255, 255, 255), -- White
			background = Color3.fromRGB(10, 10, 30), -- Dark Blue
			text = Color3.fromRGB(255, 255, 255) -- White
		},
		gradient = {
			ColorSequenceKeypoint.new(0, Color3.fromRGB(0, 100, 200)),
			ColorSequenceKeypoint.new(0.5, Color3.fromRGB(0, 150, 255)),
			ColorSequenceKeypoint.new(1, Color3.fromRGB(255, 255, 255))
		}
	},
	
	SPACE = {
		name = "Space",
		colors = {
			primary = Color3.fromRGB(75, 0, 130), -- Indigo
			secondary = Color3.fromRGB(138, 43, 226), -- Blue Violet
			accent = Color3.fromRGB(255, 255, 0), -- Yellow
			background = Color3.fromRGB(0, 0, 0), -- Black
			text = Color3.fromRGB(255, 255, 255) -- White
		},
		gradient = {
			ColorSequenceKeypoint.new(0, Color3.fromRGB(75, 0, 130)),
			ColorSequenceKeypoint.new(0.5, Color3.fromRGB(138, 43, 226)),
			ColorSequenceKeypoint.new(1, Color3.fromRGB(255, 255, 0))
		}
	},
	
	SUNSET = {
		name = "Sunset",
		colors = {
			primary = Color3.fromRGB(255, 69, 0), -- Red Orange
			secondary = Color3.fromRGB(255, 140, 0), -- Dark Orange
			accent = Color3.fromRGB(255, 215, 0), -- Gold
			background = Color3.fromRGB(30, 15, 5), -- Dark Brown
			text = Color3.fromRGB(255, 255, 255) -- White
		},
		gradient = {
			ColorSequenceKeypoint.new(0, Color3.fromRGB(255, 69, 0)),
			ColorSequenceKeypoint.new(0.5, Color3.fromRGB(255, 140, 0)),
			ColorSequenceKeypoint.new(1, Color3.fromRGB(255, 215, 0))
		}
	},
	
	-- Custom themes can be added here
	CUSTOM1 = {
		name = "Custom Theme 1",
		colors = {
			primary = Color3.fromRGB(255, 0, 0), -- Red
			secondary = Color3.fromRGB(0, 255, 0), -- Green
			accent = Color3.fromRGB(0, 0, 255), -- Blue
			background = Color3.fromRGB(0, 0, 0), -- Black
			text = Color3.fromRGB(255, 255, 255) -- White
		},
		gradient = {
			ColorSequenceKeypoint.new(0, Color3.fromRGB(255, 0, 0)),
			ColorSequenceKeypoint.new(0.5, Color3.fromRGB(0, 255, 0)),
			ColorSequenceKeypoint.new(1, Color3.fromRGB(0, 0, 255))
		}
	}
}

-- Animation Configuration
LoadingScreenConfig.ANIMATIONS = {
	-- Mountain animation settings
	MOUNTAIN = {
		duration = 3, -- seconds
		easingStyle = Enum.EasingStyle.Sine,
		easingDirection = Enum.EasingDirection.InOut,
		repeatCount = -1, -- infinite
		reverses = true
	},
	
	-- Sun animation settings
	SUN = {
		duration = 6, -- seconds
		easingStyle = Enum.EasingStyle.Sine,
		easingDirection = Enum.EasingDirection.InOut,
		repeatCount = -1, -- infinite
		reverses = true
	},
	
	-- Particle animation settings
	PARTICLE = {
		duration = {2, 5}, -- random duration range
		easingStyle = Enum.EasingStyle.Sine,
		easingDirection = Enum.EasingDirection.InOut,
		repeatCount = -1, -- infinite
		reverses = true,
		delayRange = {0, 2} -- random delay range
	},
	
	-- Loading bar animation settings
	LOADING_BAR = {
		duration = 0.5, -- seconds
		easingStyle = Enum.EasingStyle.Quart,
		easingDirection = Enum.EasingDirection.Out,
		repeatCount = 0,
		reverses = false
	},
	
	-- Fade out animation settings
	FADE_OUT = {
		duration = 1, -- seconds
		easingStyle = Enum.EasingStyle.Quart,
		easingDirection = Enum.EasingDirection.Out,
		repeatCount = 0,
		reverses = false
	}
}

-- UI Configuration
LoadingScreenConfig.UI = {
	-- Main frame settings
	MAIN_FRAME = {
		size = UDim2.new(1, 0, 1, 0),
		position = UDim2.new(0, 0, 0, 0),
		backgroundTransparency = 0
	},
	
	-- Mountain settings
	MOUNTAIN1 = {
		size = UDim2.new(0.8, 0, 0.6, 0),
		position = UDim2.new(0.1, 0, 0.4, 0)
	},
	
	MOUNTAIN2 = {
		size = UDim2.new(0.6, 0, 0.4, 0),
		position = UDim2.new(0.3, 0, 0.6, 0)
	},
	
	MOUNTAIN3 = {
		size = UDim2.new(0.4, 0, 0.3, 0),
		position = UDim2.new(0.6, 0, 0.7, 0)
	},
	
	-- Sun settings
	SUN = {
		size = UDim2.new(0.15, 0, 0.15, 0),
		position = UDim2.new(0.8, 0, 0.1, 0)
	},
	
	-- Text settings
	LOADING_TEXT = {
		size = UDim2.new(0.6, 0, 0.1, 0),
		position = UDim2.new(0.2, 0, 0.3, 0),
		font = Enum.Font.GothamBold,
		textScaled = true
	},
	
	GAME_TITLE = {
		size = UDim2.new(0.8, 0, 0.15, 0),
		position = UDim2.new(0.1, 0, 0.1, 0),
		font = Enum.Font.GothamBold,
		textScaled = true
	},
	
	PROGRESS_TEXT = {
		size = UDim2.new(0.6, 0, 0.05, 0),
		position = UDim2.new(0.2, 0, 0.5, 0),
		font = Enum.Font.Gotham,
		textScaled = true
	},
	
	THEME_INDICATOR = {
		size = UDim2.new(0.3, 0, 0.05, 0),
		position = UDim2.new(0.35, 0, 0.85, 0),
		font = Enum.Font.Gotham,
		textScaled = true
	},
	
	-- Loading bar settings
	LOADING_BAR_BG = {
		size = UDim2.new(0.6, 0, 0.02, 0),
		position = UDim2.new(0.2, 0, 0.45, 0)
	},
	
	LOADING_BAR = {
		size = UDim2.new(0, 0, 1, 0),
		position = UDim2.new(0, 0, 0, 0)
	},
	
	-- Particle settings
	PARTICLE = {
		size = UDim2.new(0.01, 0, 0.01, 0),
		count = 10
	}
}

-- Sound Configuration
LoadingScreenConfig.SOUNDS = {
	-- Sound IDs (replace with your own)
	LOADING_START = "rbxasset://sounds/electronicpingshort.wav",
	LOADING_COMPLETE = "rbxasset://sounds/button.wav",
	THEME_CHANGE = "rbxasset://sounds/click.wav",
	
	-- Sound settings
	VOLUME = 0.5,
	PITCH = 1,
	LOOPED = false
}

-- Performance Configuration
LoadingScreenConfig.PERFORMANCE = {
	-- Frame rate settings
	TARGET_FPS = 60,
	MAX_FPS = 120,
	
	-- Memory settings
	MAX_MEMORY_MB = 100,
	CLEANUP_INTERVAL = 30, -- seconds
	
	-- Optimization settings
	ENABLE_OPTIMIZATION = true,
	REDUCE_QUALITY_ON_LOW_END = true,
	DISABLE_PARTICLES_ON_LOW_END = true
}

-- Integration Configuration
LoadingScreenConfig.INTEGRATION = {
	-- Auto loading
	ENABLE_AUTO_LOADING = true,
	ENABLE_LEVEL_TRANSITION = true,
	ENABLE_ASSET_LOADING = true,
	
	-- Loading delay
	LOADING_DELAY = 2, -- seconds
	
	-- Theme by level
	THEME_BY_LEVEL = {
		[1] = "MOUNTAIN",
		[2] = "OCEAN",
		[3] = "SPACE",
		[4] = "SUNSET"
	},
	
	-- Default theme
	DEFAULT_THEME = "MOUNTAIN"
}

-- Demo Configuration
LoadingScreenConfig.DEMO = {
	-- Auto demo
	ENABLE_AUTO_DEMO = true,
	DEMO_INTERVAL = 10, -- seconds
	DEMO_DURATION = 5, -- seconds
	
	-- Controls
	ENABLE_KEYBOARD_SHORTCUTS = true,
	ENABLE_MOUSE_CONTROLS = true,
	
	-- Keyboard shortcuts
	KEYBOARD_SHORTCUTS = {
		[Enum.KeyCode.F1] = "start_stop_demo",
		[Enum.KeyCode.F2] = "next_theme",
		[Enum.KeyCode.F3] = "previous_theme",
		[Enum.KeyCode.F4] = "show_theme_info",
		[Enum.KeyCode.F5] = "show_loading_screen",
		[Enum.KeyCode.F6] = "hide_loading_screen",
		[Enum.KeyCode.F7] = "start_auto_loading",
		[Enum.KeyCode.F8] = "update_progress_50",
		[Enum.KeyCode.F9] = "update_progress_100"
	},
	
	-- Mouse controls
	MOUSE_CONTROLS = {
		[Enum.UserInputType.MouseButton1] = "next_theme",
		[Enum.UserInputType.MouseButton2] = "previous_theme",
		[Enum.UserInputType.MouseButton3] = "show_theme_info"
	}
}

-- Export configuration
return LoadingScreenConfig