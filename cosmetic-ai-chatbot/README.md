# Cosmetic AI Chatbot - Backend

Ce dossier contient un backend FastAPI minimal utilisé par le site CastleCo pour répondre aux requêtes du chatbot.

Pré-requis
- Python 3.8+
- Installer les dépendances :

```powershell
cd cosmetic-ai-chatbot
python -m pip install -r requirements.txt
```

Lancer le serveur (Windows)
- PowerShell :

```powershell
cd cosmetic-ai-chatbot
./run_backend.ps1
```

- ou via CMD :

```cmd
cd cosmetic-ai-chatbot
run_backend.bat
```

Endpoints principaux
- GET / -> health check
- GET /products -> liste des produits (depuis `app/products.json`)
- POST /chat -> body JSON `{ "message": "..." }` renvoie `{ "response": "..." }`

Tests
- Un script `test_backend.py` est fourni pour vérifier rapidement le service :

```powershell
python test_backend.py
```

Intégration PHP
- Le contrôleur `ChatbotController` poste vers l'URL `http://127.0.0.1:8000/chat` par défaut. Vous pouvez changer la variable d'environnement `CHATBOT_BACKEND` pour pointer ailleurs.

Support
- Si le serveur ne démarre pas, vérifiez que `uvicorn` est installé et que Python est accessible dans le PATH.
