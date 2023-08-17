import { createApp, ref } from 'https://unpkg.com/vue@3/dist/vue.esm-browser.js'


export const commentaires = ref([])
export const texte1 = ref("")
export const texte2 = ref("")
export const notes = ref([])
export const etoile1 = ref("")
export const etoile2 = ref("")
export const nom_client = ref(localStorage.getItem('nom_client') || '')
export const carte = ref(localStorage.getItem('afficher_carte') === 'true')
export const entree = ref('potage2.png')
export const repas = ref('tartare2.png')
export const dessert = ref('brownie2.png')
export const image_commentaire = ref('tartare2.png')
export const balise_video = ref(null)
export const video = ref("pexels-bonus-studio-5498709-3840x2160-25fps.mp4")


//fetch

fetch('public/commentaires.json')
  .then(resp => resp.json())
  .then(fichier => {
    commentaires.value = fichier.commentaires


    function changerCommentaire() {
      let index1 = Math.floor(Math.random() * commentaires.value.length)
      let index2 = Math.floor(Math.random() * commentaires.value.length)

      // Vérifier que les indices générés sont différents
      while (index2 === index1) {
        index2 = Math.floor(Math.random() * commentaires.value.length)
      }

      // Récupérer les textes correspondants aux indices générés

      texte1.value = commentaires.value[index1].texte
      texte2.value = commentaires.value[index2].texte

      const note1 = commentaires.value[index1].note
      const note2 = commentaires.value[index2].note

      etoile1.value = convertirNoteEnEtoile(note1)
      etoile2.value = convertirNoteEnEtoile(note2)
    }


    setInterval(changerCommentaire, 5000)
    changerCommentaire()

  })

//fonction pour le scroll vers la section burger
document.addEventListener('DOMContentLoaded', function () {
  const urlParams = new URLSearchParams(window.location.search)
  const sectionParam = urlParams.get('section')

  if (sectionParam && sectionParam.toLowerCase() === 'burger') {
    const sections = document.querySelectorAll('.mot_clef')

    for (const section of sections) {
      if (section.textContent.toLowerCase().includes('burger')) {
        section.scrollIntoView({ behavior: 'smooth' })
        break
      }
    }
  }
})

//constante pour changer les images des commentaires
export const images_commentaire = ref([
  'tartare2.png',
  'calamar3.png',
  'burger4.png',
  'ribs2.png',
  'brownie3.png'
])

export function ChangerImageCommentaire() {
  let image_commentaire_random = Math.floor(Math.random() * images_commentaire.value.length)
  image_commentaire.value = images_commentaire.value[image_commentaire_random]
}



setInterval(ChangerImageCommentaire, 4000)



//changement random des images sur la page d'accueil
export const images_entree = ref([
  'potage2.png',
  'calamar2.png',
  'nachos2.png'
])

export const images_repas = ref([
  'tartare2.png',
  'burger3.png',
  'salade_grecque.jpg'
])

export const images_dessert = ref([
  'brownie2.png',
  'gateau2.png',
  'cupcake2.png'
])




//afficher les notes en étoiles
export function convertirNoteEnEtoile(note) {
  let etoiles = ""
  const etoile_pleine = Math.floor(note)
  const moitié_etoile = note - etoile_pleine

  for (let i = 0; i < etoile_pleine; i++) {
    etoiles += ("<img src='public/img/etoile.png' alt='etoile' width='40px'>")
  }
  if (moitié_etoile >= 0.5) {
    etoiles += ("<img src='public/img/etoile2.png' alt='etoile' width='25px'>")
  }
  return etoiles
}




//changer les images random
export function changerImage() {
  let images_entree_random = Math.floor(Math.random() * images_entree.value.length)
  entree.value = images_entree.value[images_entree_random]

  let images_repas_random = Math.floor(Math.random() * images_repas.value.length)
  repas.value = images_repas.value[images_repas_random]

  let images_dessert_random = Math.floor(Math.random() * images_dessert.value.length)
  dessert.value = images_dessert.value[images_dessert_random]

}

setInterval(changerImage, 5000)


//afficher la petite carte pour l'infolettre
export function afficherCarte() {
  carte.value = true
  localStorage.setItem('afficher_carte', 'true')
}


//la retirer
export function retirerCarte() {
  carte.value = false
}








