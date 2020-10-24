using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class PauseController : MonoBehaviour
{
    private Orbit[] orbitElements;
    private Rotation[] rotationElements;

    private Text orbitButtonText;
    private Text rotationButtonText;

    private bool isOrbitPaused;
    private bool isRotationPaused;

    public void Start()
    {
        isOrbitPaused = false;
        //Vai buscar todos os elementos que estão a orbitar algum objeto
        orbitElements = FindObjectsOfType<Orbit>();

        isRotationPaused = false;
        //Vai buscar todos os elementos que estão a rodar sobre si próprios
        rotationElements = FindObjectsOfType<Rotation>();

        //Vai buscar os botões de pause
        var buttons = FindObjectsOfType<Button>();
        orbitButtonText = buttons[0].GetComponentInChildren<Text>();
        rotationButtonText = buttons[1].GetComponentInChildren<Text>();
    }

    private void PauseOrbit()
    {
        foreach(Orbit orbit in orbitElements)
        {
            orbit.SetPause(true);
        }
    }

    private void UnpauseOrbit()
    {
        foreach (Orbit orbit in orbitElements)
        {
            orbit.SetPause(false);
        }
    }

    public void ControlOrbitPause()
    {
        if (isOrbitPaused)
        {
            UnpauseOrbit();
            isOrbitPaused = false;
            orbitButtonText.text = "Pause Orbit";
        }
        else
        {
            PauseOrbit();
            isOrbitPaused = true;
            orbitButtonText.text = "Unpause Orbit";
        }
    }

    private void PauseRotation()
    {
        foreach (Rotation rotation in rotationElements)
        {
            rotation.SetPause(true);
        }
    }

    private void UnpauseRotation()
    {
        foreach (Rotation rotation in rotationElements)
        {
            rotation.SetPause(false);
        }
    }

    public void ControlRotationPause()
    {
        if (isRotationPaused)
        {
            UnpauseRotation();
            isRotationPaused = false;
            rotationButtonText.text = "Pause Rotation";
        }
        else
        {
            PauseRotation();
            isRotationPaused = true;
            rotationButtonText.text = "Unpause Rotation";
        }
    }
}
