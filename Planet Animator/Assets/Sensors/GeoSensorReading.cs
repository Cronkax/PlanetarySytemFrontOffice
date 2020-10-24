using System;
using UnityEngine;
using System.Collections;

public class GeoSensorReading
{
    public DateTime timestamp;

    public GeoSensorReading()
    {
        timestamp = DateTime.Now;
    }

    public string timestampToString()
    {
        return timestamp.Hour + ":" + timestamp.Minute + ":" + timestamp.Second;
    }

}
