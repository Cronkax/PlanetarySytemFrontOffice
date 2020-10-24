using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.Android;

public class Orbit : MonoBehaviour
{

    public GameObject focus;
    public float a;
    public float b;
    public float anglePerSecond;
    public float angle = 0;

    private bool isPaused;

    public void Start()
    {
        SetPause(false);   
    }

    // Update is called once per frame
    public void Update()
    {
        if (!isPaused)
        {
            var x = focus.transform.position.x;
            var z = focus.transform.position.z;

            transform.position = GetXY(x, z, a, b, Deg2Rad(angle));

            angle += anglePerSecond * Time.deltaTime;

            if (angle >= 360f)
            { 
                angle -= 360f;
            }
        }     
    }

    public float Deg2Rad(float degree)
    {
        return degree * Mathf.Deg2Rad;
    }

    public Vector3 GetXY(float x1, float y1, float a, float b, float t)
    {
        float c = Mathf.Sqrt(Mathf.Pow(a, 2) - Mathf.Pow(b, 2));
        float x = x1 + c + a * Mathf.Cos(t);
        float y = y1 + b * Mathf.Sin(t);
        return new Vector3(x, 0, y);
    }

    public void SetPause(bool pause)
    {
        isPaused = pause;
    }

}
