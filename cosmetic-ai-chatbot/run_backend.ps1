# Run FastAPI backend with uvicorn (PowerShell)
python -m uvicorn app.main:app --host 127.0.0.1 --port 8000 --reload
Read-Host "Appuyez sur Entrée pour quitter"
