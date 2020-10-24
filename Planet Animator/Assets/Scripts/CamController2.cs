using System.Collections;
using System.Collections.Generic;
using System.Security.Cryptography;
using System.Threading;
using UnityEngine;

public class CamController2 : MonoBehaviour
{
	//variáveis que permitem controlar a velocidade das várias ações da câmara
    public float zoomFactor = 3f;
    public float zoomLerpSpeed = 10;
    public float movePosition = 0.2f;
    public float rotateSpeed = 0.2f;
		
	//variáveis auxiliares
    private Camera cam;
    private float targetZoom;
    private float rotateX;
    private float rotateY;

    // Start is called before the first frame update
    void Start()
    {
        cam = Camera.main; //variável que controla a câmara principal da cena
        targetZoom = cam.orthographicSize; //guarda o valor ortográfico da câmara
    }

    // Update is called once per frame
    void Update()
    {
		//ZOOM
        float scrollData;
        scrollData = Input.GetAxis("Mouse ScrollWheel"); //recebe o valor da roda do rato
        targetZoom -= scrollData * zoomFactor; //atualiza o valor do zoom
				
		//utiliza a função Mathf.Lerp() para atualizar o valor ortográfico da câmara
        cam.orthographicSize = Mathf.Lerp(cam.orthographicSize, targetZoom, Time.deltaTime * zoomLerpSpeed);
				
		//POSIÇÃO
		//o movimento da câmara acontece ao clicar-se no botão esquerdo do rato
        if (Input.GetMouseButton(0))
        {
			//utiliza-se os valores dos eixos X e Y da posição do rato para alterar a posição da câmara,
			//não se atualiza o valor do eixo Z porque usa-se o valor ortográfico para fazer zoom
            transform.position = new Vector3(
                transform.position.x - Input.GetAxis("Mouse X") * movePosition * Time.deltaTime,
                transform.position.y - Input.GetAxis("Mouse Y") * movePosition * Time.deltaTime,
                -35f
            );
        }

		//ROTAÇÃO
		//a rotação da câmara  acontece ao clicar-se no botão direito do rato 
        if (Input.GetMouseButton(1))
        {
			//utiliza-se os valores dos eixos X e Y da posição do rato para alterar a rotação da câmara
            rotateX += Input.GetAxis("Mouse X") * rotateSpeed;
            rotateY -= Input.GetAxis("Mouse Y") * rotateSpeed;
            transform.eulerAngles = new Vector3(rotateY, rotateX, 0.0f);
        }
    }
}
