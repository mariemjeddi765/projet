from fastapi import FastAPI, HTTPException
from pydantic import BaseModel
import requests
from .prompt import build_prompt
import json
from typing import List
from fastapi.middleware.cors import CORSMiddleware
import logging
from pathlib import Path

app = FastAPI(title="CastleCo")

app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Autorise toutes les origines
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

logging.basicConfig(level=logging.INFO)

BASE_DIR = Path(__file__).resolve().parent
PRODUCTS_PATH = BASE_DIR / "products.json"

# Load products from JSON file with error handling
def load_products():
    try:
        with open(PRODUCTS_PATH, "r", encoding="utf-8") as f:
            return json.load(f)
    except FileNotFoundError:
        logging.warning("products.json introuvable, retour d'une liste vide")
        return []
    except json.JSONDecodeError:
        raise HTTPException(status_code=500, detail="Le fichier products.json est corrompu.")

# Save products to JSON file
def save_products(products):
    with open(PRODUCTS_PATH, "w", encoding="utf-8") as f:
        json.dump(products, f, ensure_ascii=False, indent=4)

class Product(BaseModel):
    id: int
    name: str
    price: float
    skin_type: str
    description: str

class ChatRequest(BaseModel):
    message: str

def generate_response(products, user_message):
    """Generate a concise, professional response based on products and user message."""
    user_message_clean = user_message.strip()
    user_message_lower = user_message_clean.lower()

    if not user_message_clean:
        return "Pouvez-vous préciser votre besoin (type de produit ou type de peau) ?"

    greetings = ["hi", "hello", "bonjour", "salut", "ça va", "comment ça va"]
    if any(greeting in user_message_lower for greeting in greetings):
        return "Bonjour ! Bienvenue chez CastleCo. Dites-moi si vous cherchez plutôt un nettoyant, un soin ou un solaire."

    order_keywords = ["commande", "commander", "acheter", "payer", "livraison", "passer une commande"]
    price_keywords = ["prix", "combien", "coût", "cout", "tarif"]
    stock_keywords = ["stock", "dispo", "disponible", "rupture"]
    ingredient_keywords = ["ingrédient", "ingredient", "composition", "compo", "allergie", "réaction", "reaction"]
    routine_keywords = ["routine", "étape", "etape", "ordre", "matin", "soir"]

    if any(word in user_message_lower for word in order_keywords):
        return (
            "Pour commander : 1) Choisissez le produit, 2) Envoyez-nous le nom et la quantité, "
            "3) Nous confirmons le paiement et la livraison."
        )

    if any(word in user_message_lower for word in price_keywords):
        return "Les prix sont indiqués avec chaque produit. Dites-moi lequel vous intéresse pour confirmer le tarif."

    if any(word in user_message_lower for word in stock_keywords):
        return "La plupart des produits listés sont en stock. Indiquez le nom pour vérifier la disponibilité."

    if any(word in user_message_lower for word in ingredient_keywords):
        return "Indiquez le produit et l'ingrédient à éviter, je vous dis si c'est compatible."

    if any(word in user_message_lower for word in routine_keywords):
        return "Routine rapide : 1) Nettoyant, 2) Sérum, 3) Crème, 4) SPF le matin. Besoin d'un détail sur un produit ?"

    best_keywords = ["meilleur", "meilleure", "plus adapté", "plus efficace", "top", "recommande"]
    request_best = any(keyword in user_message_lower for keyword in best_keywords)

    product_type_priority = [
        ("solaire", ["spf", "solaire", "uv", "sun", "écran", "ecran", "protection solaire", "sunblock"]),
        ("nettoyant", ["nettoyant", "gel", "mousse", "cleanser", "lavant"]),
        ("serum", ["sérum", "serum", "ampoule"]),
        ("masque", ["masque", "mask"]),
        ("huile", ["huile", "oil"]),
        ("creme", ["crème", "creme", "cream", "soin", "hydratant"]),
    ]

    def detect_requested_type(message: str):
        for p_type, keywords in product_type_priority:
            if any(word in message for word in keywords):
                return p_type
        return None

    requested_type = detect_requested_type(user_message_lower)

    dry_keywords = ["sec", "sèche", "seche", "desséché", "desseche", "peau sèche", "peau seche", "hydrat", "aride"]
    oily_keywords = ["gras", "grasse", "brillant", "acné", "acne", "peau grasse", "sébum", "sebum", "purifiant"]
    sensitive_keywords = ["sensible", "irrité", "irrite", "rougeur", "rougeurs", "allergie", "inflammation", "apaisant"]
    mature_keywords = ["ride", "rides", "vieillissement", "anti-âge", "anti age", "jeunesse", "maturité", "mature", "q10", "raffermit"]

    relevant_products = []
    skin_type_detected = None

    message_words = [w for w in user_message_lower.replace(",", " ").replace(";", " ").split() if len(w) > 2]

    message_substrings = set()
    for word in message_words:
        message_substrings.add(word)
        for i in range(len(word) - 2):
            message_substrings.add(word[i:i+4])

    for product in products:
        product_name_lower = product.get("name", "").lower()
        product_desc_lower = product.get("description", "").lower()

        if any(sub in product_name_lower for sub in message_substrings) or any(
            sub in product_desc_lower for sub in message_substrings
        ):
            relevant_products.append(product)

    skin_type_products = []
    if any(keyword in user_message_lower for keyword in dry_keywords):
        skin_type_detected = "sèche"
        skin_type_products = [
            p
            for p in products
            if p.get("skin_type", "").lower() in ["sèche", "sec", "dry"]
            or p.get("skin_type", "").lower() == "tous"
        ]
    elif any(keyword in user_message_lower for keyword in oily_keywords):
        skin_type_detected = "grasse"
        skin_type_products = [
            p
            for p in products
            if p.get("skin_type", "").lower() in ["grasse", "gras", "oily", "mixte"]
            or p.get("skin_type", "").lower() == "tous"
        ]
    elif any(keyword in user_message_lower for keyword in sensitive_keywords):
        skin_type_detected = "sensible"
        skin_type_products = [
            p
            for p in products
            if p.get("skin_type", "").lower() == "sensible" or p.get("skin_type", "").lower() == "tous"
        ]
    elif any(keyword in user_message_lower for keyword in mature_keywords):
        skin_type_detected = "mature"
        skin_type_products = [
            p
            for p in products
            if p.get("skin_type", "").lower() == "mature" or p.get("skin_type", "").lower() == "tous"
        ]

    if skin_type_detected:
        if relevant_products:
            filtered = [
                p
                for p in relevant_products
                if p.get("skin_type", "").lower() in {s.get("skin_type", "").lower() for s in skin_type_products}
            ]
            if filtered:
                relevant_products = filtered
            else:
                relevant_products = skin_type_products
        else:
            relevant_products = skin_type_products

    if not relevant_products and "autre" in user_message_lower:
        return "Dites-moi le type de produit (crème, sérum, nettoyant) et votre besoin principal."

    if requested_type and relevant_products:
        filtered = [
            p
            for p in relevant_products
            if requested_type in p.get("name", "").lower() or requested_type in p.get("description", "").lower()
        ]
        if filtered:
            relevant_products = filtered

    if not relevant_products:
        return "Je n'ai pas trouvé de produit correspondant. Cherchez-vous une crème, un sérum ou un nettoyant ?"

    def collect(types):
        return [p for p in relevant_products if p.get("skin_type", "").lower() in types]

    dry_products = collect({"sèche", "sec", "dry"})
    oily_products = collect({"grasse", "gras", "oily", "mixte"})
    sensitive_products = collect({"sensible"})
    mature_products = collect({"mature"})
    universal_products = collect({"tous"})

    other_products = [
        p
        for p in relevant_products
        if p not in dry_products + oily_products + sensitive_products + mature_products + universal_products
    ]

    if skin_type_detected == "sèche":
        order_groups = [dry_products, universal_products, oily_products, sensitive_products, mature_products, other_products]
    elif skin_type_detected == "grasse":
        order_groups = [oily_products, universal_products, dry_products, sensitive_products, mature_products, other_products]
    elif skin_type_detected == "sensible":
        order_groups = [sensitive_products, universal_products, dry_products, oily_products, mature_products, other_products]
    elif skin_type_detected == "mature":
        order_groups = [mature_products, universal_products, dry_products, oily_products, sensitive_products, other_products]
    else:
        order_groups = [dry_products, oily_products, sensitive_products, mature_products, universal_products, other_products]

    prioritized = []
    for group in order_groups:
        prioritized.extend(group)

    seen = set()
    unique_products = []
    for p in prioritized:
        if p.get("id") not in seen:
            seen.add(p.get("id"))
            unique_products.append(p)

    def usage_hint(product):
        name_lower = product.get("name", "").lower()
        desc_lower = product.get("description", "").lower()
        if "masque" in name_lower:
            return "1 à 2 fois par semaine"
        if "nuit" in name_lower:
            return "Le soir sur peau propre"
        if "sérum" in name_lower or "serum" in name_lower:
            return "Quelques gouttes avant la crème"
        if "huile" in name_lower:
            return "2-3 gouttes sur peau légèrement humide"
        if any(word in name_lower for word in ["gel", "nettoyant", "mousse"]):
            return "Matin et soir, masser puis rincer"
        if "solaire" in name_lower or "spf" in name_lower:
            return "Chaque matin, réappliquer en cas d'exposition"
        if "hydrat" in desc_lower:
            return "Matin et soir"
        return None

    def format_intro():
        if skin_type_detected == "sèche":
            return "Pour une peau sèche :"
        if skin_type_detected == "grasse":
            return "Pour une peau grasse :"
        if skin_type_detected == "sensible":
            return "Pour une peau sensible :"
        if skin_type_detected == "mature":
            return "Pour une peau mature :"
        return "Je vous recommande :"

    if request_best and unique_products:
        hero = unique_products[0]
        if requested_type:
            type_filtered = [
                p
                for p in unique_products
                if requested_type in p.get("name", "").lower() or requested_type in p.get("description", "").lower()
            ]
            if type_filtered:
                hero = type_filtered[0]
        reason_parts = []
        if skin_type_detected:
            reason_parts.append(f"Adapté à la peau {skin_type_detected}.")
        if requested_type:
            reason_parts.append(f"C'est le {requested_type} le plus pertinent ici.")
        reason = " ".join(reason_parts) or "C'est le plus cohérent avec votre demande."
        usage = usage_hint(hero)
        usage_text = f"Utilisation : {usage}." if usage else ""
        return (
            f"Le plus adapté : {hero.get('name', 'Produit')} - {hero.get('price', 'N/A')} DT.\n"
            f"{reason}\n"
            f"{usage_text}"
        ).strip()

    intro = format_intro()
    limited_products = unique_products[:2]

    lines = [intro]
    for idx, product in enumerate(limited_products, 1):
        product_name = product.get("name", "Produit")
        product_price = product.get("price", "N/A")
        product_desc = product.get("description", "N/A")
        product_skin = product.get("skin_type", "Tous types")

        lines.append(f"{idx}. {product_name} — {product_price} DT ({product_skin})")
        lines.append(f"   {product_desc}")

        hint = usage_hint(product)
        if hint:
            lines.append(f"   Usage : {hint}")

    if not skin_type_detected and not requested_type and not request_best:
        lines.append("Besoin de préciser votre type de peau pour affiner ?")

    return "\n".join(lines)

@app.post("/chat")
async def chat_endpoint(chat_request: ChatRequest):
    logging.info(f"Received message: {chat_request.message}")
    products = load_products()  # Load products within the function
    user_message = chat_request.message

    try:
        response_text = generate_response(products, user_message)
        logging.info("Response generated successfully.")
        return {"response": response_text}
    except HTTPException as e:
        logging.error("Error while generating response (HTTPException)", exc_info=True)
        return {"response": e.detail}
    except Exception as e:
        logging.error("Error while generating response", exc_info=True)
        return {"response": "Une erreur est survenue. Veuillez réessayer."}

@app.get("/")
def home():
    return {"message": "Le serveur fonctionne !"}

@app.get("/products", response_model=List[Product])
async def list_products():
    return load_products()

@app.post("/products", response_model=Product)
async def add_product(product: Product):
    products = load_products()
    if any(p["id"] == product.id for p in products):
        raise HTTPException(status_code=400, detail="Product with this ID already exists.")
    products.append(product.dict())
    save_products(products)
    return product

@app.put("/products/{product_id}", response_model=Product)
async def update_product(product_id: int, updated_product: Product):
    products = load_products()
    for i, product in enumerate(products):
        if product["id"] == product_id:
            products[i] = updated_product.dict()
            save_products(products)
            return updated_product
    raise HTTPException(status_code=404, detail="Product not found.")

@app.delete("/products/{product_id}")
async def delete_product(product_id: int):
    products = load_products()
    filtered_products = [p for p in products if p["id"] != product_id]
    if len(filtered_products) == len(products):
        raise HTTPException(status_code=404, detail="Product not found.")
    save_products(filtered_products)
    return {"message": "Product deleted successfully."}
