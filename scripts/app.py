
from PIL import Image
from collections import Counter
import matplotlib.pyplot as plt

# === Paramètres ===
image_path = "test.png"  # Remplace par ton chemin réel
largeur_reelle_pouces = 19.25
hauteur_reelle_pouces = 30
nb_couleurs = 6  # Nombre de couleurs finales (à adapter)
seuil_noir = 50

# === Chargement et quantification ===
img = Image.open(image_path).convert("RGB")
img_quantized = img.convert("P", palette=Image.ADAPTIVE, colors=nb_couleurs).convert("RGB")

pixels = list(img_quantized.getdata())
largeur_pixels, hauteur_pixels = img.size
total_pixels = largeur_pixels * hauteur_pixels
surface_totale = largeur_reelle_pouces * hauteur_reelle_pouces
surface_par_pixel = surface_totale / total_pixels

# === Regroupement des pixels ===
compte_couleurs = Counter()
for pixel in pixels:
    r, g, b = pixel
    if r <= seuil_noir and g <= seuil_noir and b <= seuil_noir:
        compte_couleurs["NOIR_PROFOND"] += 1
    else:
        compte_couleurs[pixel] += 1

# === Création des données pour l'affichage ===
donnees = []
for couleur, count in compte_couleurs.items():
    surface = count * surface_par_pixel
    donnees.append((couleur, count, surface))

# === Affichage graphique ===
fig, ax = plt.subplots(figsize=(9, 5))

for i, (couleur, count, surface) in enumerate(sorted(donnees, key=lambda x: x[2], reverse=True)):
    if couleur == "NOIR_PROFOND":
        color_hex = "#000000"
        label = f"{count} px | {surface:.2f} po²"
        text_color = "white"
    else:
        r, g, b = couleur
        color_hex = f"#{r:02x}{g:02x}{b:02x}"
        label = f"{count} px | {surface:.2f} po²"
        text_color = "white" if (r*0.299 + g*0.587 + b*0.114) < 100 else "black"

    ax.barh(i, surface, color=color_hex, edgecolor='black')
    ax.text(surface * 0.02, i, label, va='center', fontsize=9, color=text_color)

ax.set_yticks([])
ax.set_xlabel("Surface (en pouces carrés)")
ax.set_title("Surface occupée par chaque couleur")
ax.set_xlim(0, max(x[2] for x in donnees) * 1.2)
plt.tight_layout()
plt.show()
