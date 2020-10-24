using UnityEngine;

public class GeoGyrReading : GeoSensorReading
{
    public Quaternion attitude;

    public override string ToString()
    {
        return "Rotation Rate : " + attitude.ToString() + " / " + timestampToString();
    }
}
