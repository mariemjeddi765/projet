@echo off
REM Run FastAPI backend with uvicorn on port 8000
python -m uvicorn app.main:app --host 127.0.0.1 --port 8000 --reload
pause
