using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class MouseOver : MonoBehaviour
{
    public Text planetText; //objeto de texto que vai apresentar o nome dos planetas

	//esta função é sempre ativada quando o rato está sobre o objeto
	//que possuiu este script, mas só funciona se o objeto tiver um collider
	//pois é o collider que vai verificar se existiu interação entre o objeto e o rato
	void OnMouseOver(){

		//quando esta função é ativada o conteúdo do objeto de texto 'planetText'
		//é substituido pela tag do objeto que possui este script
        planetText.text = gameObject.tag;
	}
}
