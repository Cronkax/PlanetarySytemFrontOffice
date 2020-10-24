using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class Rotation : MonoBehaviour
{
    public float anglePerSecond;
    public float axisAngle;

    private bool isPaused;

    void Start()
    {
        isPaused = false;
        transform.Rotate(new Vector3(axisAngle, 0, 0));
    }

    void Update()
    {
        if (!isPaused)
        {
            transform.Rotate(new Vector3(0, -anglePerSecond * Time.deltaTime, 0));
        }
    }

    public void SetPause(bool pause)
    {
        isPaused = pause;
    }
}
