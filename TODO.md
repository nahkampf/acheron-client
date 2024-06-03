<?php

# SENSOR
- [X] Update sensor list
- [X] Update signal list
  - [ ] On each update go through the list and remove orphaned signals
  - [ ] "Designation" editor for signals
- [ ] Update SURFOPS position list
- [ ] Bonus idea: Sensors can become "decalibrated". If possible make some interesting interface where sensor operators can "recalibrate" them. (look at https://jsfiddle.net/kwn83xtv/)

# SIGINT
- [ ] List all "handled" signals
- [ ] Dialog popup to show spectrogram and soundfile player
  - [ ] Sidebar to show drilldown identifier (carrier waves, data packets)
  - [ ] "Select" identification
  - [ ] Send API call to server with updated info

# SCIENCE/DECRYPT
- [ ] List all signals
- [ ] Split full screen "decryptor":
  - [ ] "Hexeditor" of signal data
  - [ ] Sideview of known phrases
  - [ ] "Finalize message"
- [ ] Soil/air/water sample data
  - [ ] Add new sample
  - [ ] Graphs

# ARCHIVE
- [ ] Table w/ filters for all known machine types

# GEOLOC
- [ ] Show autoupdating map
- [ ] "Layer" toggle: SENSORS, REFPOINTS, SURFOPS track, SIGNALS
- [ ] Drop a needle (position + designation -> select from list)
- [ ] Clickable markers (info popup)
- [ ] Write out designation beneath marker?

# BIOMONITOR
- [X] Show data
- [ ] Audible alerts
- [X] Get data from server
- Simulate fluctuations in vitals within "preset" ranges (based on server dataö)

# General
## Bootup/raspberry stuff
- [ ] Set/edit environment variables on bootup
- [ ] Boot into firefox kiosk mode
- [ ] Set autplay sound flags on browser
- [ ] Make sure rdesktop works (wonky resolution if running headless)
  
## General terminal stuff
- [ ] Display diegetic time on header bar
- [ ] Display station name on header bar
- [ ] Display network state / last successful server update on status bar
- [X] Backend proxy for API calls
  - [ ] Error handling
  - [ ] Use ENV variables
- [X] Alert states
  - [ ] Make alert states not cancellable by ESC
  