@echo off
setlocal

REM ============================================================
REM Build a Windows .exe for end users (PyInstaller)
REM Project files expected in this folder:
REM   app_ui.py        (entry point)
REM   main.py          (logic)
REM   app_design.py    (UI theme)
REM ============================================================

REM 1) Create venv (only first time)
if not exist .venv (
  py -m venv .venv
)

REM 2) Activate venv
call .venv\Scripts\activate.bat

REM 3) Install dependencies
python -m pip install --upgrade pip
python -m pip install -r requirements.txt
python -m pip install pyinstaller

REM 4) Clean previous builds
rmdir /s /q build 2>nul
rmdir /s /q dist 2>nul
del /q *.spec 2>nul

REM 5) Build
REM --noconsole hides the console window (Tkinter GUI app)
REM --onefile makes a single exe (slower startup). Consider removing it for faster startup.
pyinstaller --noconsole --onefile --name "ExcelObjAddrTransfer" app_ui.py

echo.
echo Build complete.
echo Your EXE is in: dist\ExcelObjAddrTransfer.exe
echo.
pause
endlocal
