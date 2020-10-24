using UnityEngine;

public class GeoAccReading : GeoSensorReading
{
    public Vector3 acceleration;

    public override string ToString()
    {
        return "Acceleration : " + acceleration.ToString() + " / " + timestampToString();
    }
}
