# CMAKE generated file: DO NOT EDIT!
# Generated by "MinGW Makefiles" Generator, CMake Version 3.9

# Delete rule output on recipe failure.
.DELETE_ON_ERROR:


#=============================================================================
# Special targets provided by cmake.

# Disable implicit rules so canonical targets will work.
.SUFFIXES:


# Remove some rules from gmake that .SUFFIXES does not remove.
SUFFIXES =

.SUFFIXES: .hpux_make_needs_suffix_list


# Produce verbose output by default.
VERBOSE = 1

# Suppress display of executed commands.
$(VERBOSE).SILENT:


# A target that is always out of date.
cmake_force:

.PHONY : cmake_force

#=============================================================================
# Set environment variables for the build.

SHELL = cmd.exe

# The CMake executable.
CMAKE_COMMAND = "C:\Program Files\JetBrains\CLion 2017.3.3\bin\cmake\bin\cmake.exe"

# The command to remove a file.
RM = "C:\Program Files\JetBrains\CLion 2017.3.3\bin\cmake\bin\cmake.exe" -E remove -f

# Escaping for special characters.
EQUALS = =

# The top-level source directory on which CMake was run.
CMAKE_SOURCE_DIR = C:\Users\Vadim\CLionProjects\HW01\HW01

# The top-level build directory on which CMake was run.
CMAKE_BINARY_DIR = C:\Users\Vadim\CLionProjects\HW01\HW01\cmake-build-debug

# Include any dependencies generated for this target.
include CMakeFiles/HW01.dir/depend.make

# Include the progress variables for this target.
include CMakeFiles/HW01.dir/progress.make

# Include the compile flags for this target's objects.
include CMakeFiles/HW01.dir/flags.make

CMakeFiles/HW01.dir/src/hw01.cpp.obj: CMakeFiles/HW01.dir/flags.make
CMakeFiles/HW01.dir/src/hw01.cpp.obj: CMakeFiles/HW01.dir/includes_CXX.rsp
CMakeFiles/HW01.dir/src/hw01.cpp.obj: ../src/hw01.cpp
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green --progress-dir=C:\Users\Vadim\CLionProjects\HW01\HW01\cmake-build-debug\CMakeFiles --progress-num=$(CMAKE_PROGRESS_1) "Building CXX object CMakeFiles/HW01.dir/src/hw01.cpp.obj"
	C:\MinGW\bin\g++.exe  $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -o CMakeFiles\HW01.dir\src\hw01.cpp.obj -c C:\Users\Vadim\CLionProjects\HW01\HW01\src\hw01.cpp

CMakeFiles/HW01.dir/src/hw01.cpp.i: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Preprocessing CXX source to CMakeFiles/HW01.dir/src/hw01.cpp.i"
	C:\MinGW\bin\g++.exe $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -E C:\Users\Vadim\CLionProjects\HW01\HW01\src\hw01.cpp > CMakeFiles\HW01.dir\src\hw01.cpp.i

CMakeFiles/HW01.dir/src/hw01.cpp.s: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Compiling CXX source to assembly CMakeFiles/HW01.dir/src/hw01.cpp.s"
	C:\MinGW\bin\g++.exe $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -S C:\Users\Vadim\CLionProjects\HW01\HW01\src\hw01.cpp -o CMakeFiles\HW01.dir\src\hw01.cpp.s

CMakeFiles/HW01.dir/src/hw01.cpp.obj.requires:

.PHONY : CMakeFiles/HW01.dir/src/hw01.cpp.obj.requires

CMakeFiles/HW01.dir/src/hw01.cpp.obj.provides: CMakeFiles/HW01.dir/src/hw01.cpp.obj.requires
	$(MAKE) -f CMakeFiles\HW01.dir\build.make CMakeFiles/HW01.dir/src/hw01.cpp.obj.provides.build
.PHONY : CMakeFiles/HW01.dir/src/hw01.cpp.obj.provides

CMakeFiles/HW01.dir/src/hw01.cpp.obj.provides.build: CMakeFiles/HW01.dir/src/hw01.cpp.obj


CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj: CMakeFiles/HW01.dir/flags.make
CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj: CMakeFiles/HW01.dir/includes_CXX.rsp
CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj: ../src/MyLinkedList.cpp
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green --progress-dir=C:\Users\Vadim\CLionProjects\HW01\HW01\cmake-build-debug\CMakeFiles --progress-num=$(CMAKE_PROGRESS_2) "Building CXX object CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj"
	C:\MinGW\bin\g++.exe  $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -o CMakeFiles\HW01.dir\src\MyLinkedList.cpp.obj -c C:\Users\Vadim\CLionProjects\HW01\HW01\src\MyLinkedList.cpp

CMakeFiles/HW01.dir/src/MyLinkedList.cpp.i: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Preprocessing CXX source to CMakeFiles/HW01.dir/src/MyLinkedList.cpp.i"
	C:\MinGW\bin\g++.exe $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -E C:\Users\Vadim\CLionProjects\HW01\HW01\src\MyLinkedList.cpp > CMakeFiles\HW01.dir\src\MyLinkedList.cpp.i

CMakeFiles/HW01.dir/src/MyLinkedList.cpp.s: cmake_force
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green "Compiling CXX source to assembly CMakeFiles/HW01.dir/src/MyLinkedList.cpp.s"
	C:\MinGW\bin\g++.exe $(CXX_DEFINES) $(CXX_INCLUDES) $(CXX_FLAGS) -S C:\Users\Vadim\CLionProjects\HW01\HW01\src\MyLinkedList.cpp -o CMakeFiles\HW01.dir\src\MyLinkedList.cpp.s

CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj.requires:

.PHONY : CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj.requires

CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj.provides: CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj.requires
	$(MAKE) -f CMakeFiles\HW01.dir\build.make CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj.provides.build
.PHONY : CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj.provides

CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj.provides.build: CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj


# Object files for target HW01
HW01_OBJECTS = \
"CMakeFiles/HW01.dir/src/hw01.cpp.obj" \
"CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj"

# External object files for target HW01
HW01_EXTERNAL_OBJECTS =

HW01.exe: CMakeFiles/HW01.dir/src/hw01.cpp.obj
HW01.exe: CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj
HW01.exe: CMakeFiles/HW01.dir/build.make
HW01.exe: CMakeFiles/HW01.dir/linklibs.rsp
HW01.exe: CMakeFiles/HW01.dir/objects1.rsp
HW01.exe: CMakeFiles/HW01.dir/link.txt
	@$(CMAKE_COMMAND) -E cmake_echo_color --switch=$(COLOR) --green --bold --progress-dir=C:\Users\Vadim\CLionProjects\HW01\HW01\cmake-build-debug\CMakeFiles --progress-num=$(CMAKE_PROGRESS_3) "Linking CXX executable HW01.exe"
	$(CMAKE_COMMAND) -E cmake_link_script CMakeFiles\HW01.dir\link.txt --verbose=$(VERBOSE)

# Rule to build all files generated by this target.
CMakeFiles/HW01.dir/build: HW01.exe

.PHONY : CMakeFiles/HW01.dir/build

CMakeFiles/HW01.dir/requires: CMakeFiles/HW01.dir/src/hw01.cpp.obj.requires
CMakeFiles/HW01.dir/requires: CMakeFiles/HW01.dir/src/MyLinkedList.cpp.obj.requires

.PHONY : CMakeFiles/HW01.dir/requires

CMakeFiles/HW01.dir/clean:
	$(CMAKE_COMMAND) -P CMakeFiles\HW01.dir\cmake_clean.cmake
.PHONY : CMakeFiles/HW01.dir/clean

CMakeFiles/HW01.dir/depend:
	$(CMAKE_COMMAND) -E cmake_depends "MinGW Makefiles" C:\Users\Vadim\CLionProjects\HW01\HW01 C:\Users\Vadim\CLionProjects\HW01\HW01 C:\Users\Vadim\CLionProjects\HW01\HW01\cmake-build-debug C:\Users\Vadim\CLionProjects\HW01\HW01\cmake-build-debug C:\Users\Vadim\CLionProjects\HW01\HW01\cmake-build-debug\CMakeFiles\HW01.dir\DependInfo.cmake --color=$(COLOR)
.PHONY : CMakeFiles/HW01.dir/depend

