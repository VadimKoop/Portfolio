# Excel Obj. Addr Transfer — Packaging for end users (Windows)

## Recommended project layout
Put these files in one folder:
- `app_ui.py` (entry point GUI)
- `main.py` (Excel logic)
- `app_design.py` (UI theme)
- `requirements.txt`

## Build an .exe (Windows) with PyInstaller
1. Install Python 3.11+ on your build machine (same Windows version as users if possible).
2. Open **Command Prompt** in the project folder.
3. Run:

```bat
build_windows.bat
```

After build, you will get:
- `dist\ExcelObjAddrTransfer.exe`

## Distribute to users
Send them only:
- `dist\ExcelObjAddrTransfer.exe`

Users do NOT need Python installed.

## Notes / common issues
- If Excel reports "file is corrupted": make sure the output file is not open in Excel when saving.
- If you get antivirus warnings: PyInstaller one-file exes can trigger false positives. Use `--onedir` (remove `--onefile`) for fewer false positives.
- Build on the target OS: Windows exe should be built on Windows.
- For an icon: add `--icon app.ico` to the pyinstaller command (use a real .ico file).

## Optional: faster startup (recommended)
Edit `build_windows.bat` and remove `--onefile`:
```bat
pyinstaller --noconsole --name "ExcelObjAddrTransfer" app_ui.py
```
Then share the whole folder `dist\ExcelObjAddrTransfer\` with users.
