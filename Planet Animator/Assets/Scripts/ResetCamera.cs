using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.XR.ARFoundation;

public class ResetCamera : MonoBehaviour
{
    private Camera cam;

    void Start()
    {
        cam = FindObjectOfType<Camera>();   
    }

    public void resetCamera()
    {
        cam.transform.position = new Vector3(0, 0, 0);
    }
}
