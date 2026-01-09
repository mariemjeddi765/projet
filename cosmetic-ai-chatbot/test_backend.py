#!/usr/bin/env python3
import requests
import sys
import time

BASE_URL = "http://localhost:8000"

def test_backend():
    print("🧪 Test du backend...")
    try:
        # Test 1: Health check
        print("\n1️⃣ Test de connexion (docs)...")
        response = requests.get(f"{BASE_URL}/docs", timeout=5)
        print(f"   Status: {response.status_code} ✅" if response.status_code == 200 else f"   Status: {response.status_code} ❌")
        
        # Test 2: GET products
        print("\n2️⃣ Test GET /products...")
        response = requests.get(f"{BASE_URL}/products", timeout=5)
        print(f"   Status: {response.status_code}")
        if response.status_code == 200:
            products = response.json()
            print(f"   Produits trouvés: {len(products)} ✅")
        else:
            print(f"   Erreur: {response.text}")
        
        # Test 3: POST chat
        print("\n3️⃣ Test POST /chat...")
        chat_data = {"message": "Quel produit me recommandez-vous pour la peau sèche?"}
        response = requests.post(f"{BASE_URL}/chat", json=chat_data, timeout=10)
        print(f"   Status: {response.status_code}")
        if response.status_code == 200:
            data = response.json()
            print(f"   Réponse reçue ✅")
            # Le backend renvoie {'response': '...'}
            print(f"   Message: {data.get('response', data.get('message', 'N/A'))[:200]}...")
        else:
            print(f"   Erreur: {response.text}")
            
        print("\n✅ Backend fonctionne!")
        return True
        
    except requests.exceptions.ConnectionError:
        print("\n❌ Impossible de se connecter à http://localhost:8000")
        print("   Assurez-vous que le serveur est en cours d'exécution.")
        return False
    except requests.exceptions.Timeout:
        print("\n⏱️ Timeout - le serveur est lent ou ne répond pas")
        return False
    except Exception as e:
        print(f"\n❌ Erreur: {e}")
        return False

if __name__ == "__main__":
    success = test_backend()
    sys.exit(0 if success else 1)
