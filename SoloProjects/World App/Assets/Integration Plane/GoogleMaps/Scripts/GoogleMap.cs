using UnityEngine;
using System.Collections;
using System.Collections.Generic;

public class GoogleMap : MonoBehaviour
{
    public enum MapType
    {
        RoadMap,
        Satellite,
        Terrain,
        Hybrid
    }


    public bool loadOnStart = true;
    public bool autoLocateCenter = true;
    public GoogleMapLocation centerLocation;
    public int zoom = 13;
    public MapType mapType;
    public int size = 400;
    public bool doubleResolution = false;

    void Start()
    {

        if (loadOnStart) Refresh();
    }

    public void Refresh()
    {

        StartCoroutine(_Refresh());
    }

    IEnumerator _Refresh()
    {
        var url = "http://maps.googleapis.com/maps/api/staticmap";
        var qs = "";
        if (!autoLocateCenter)
        {
            if (centerLocation.address != "")
                qs += "center=" + centerLocation.address;
            else
            {
                qs += "center=" + WWW.UnEscapeURL(string.Format("{0},{1}", centerLocation.latitude, centerLocation.longitude));
            }

            qs += "&zoom=" + zoom.ToString();
        }
        qs += "&size=" + WWW.UnEscapeURL(string.Format("{0}x{0}", size));
        qs += "&scale=" + (doubleResolution ? "2" : "1");
        qs += "&maptype=" + mapType.ToString().ToLower();
        var usingSensor = false;

        qs += "&sensor=" + (usingSensor ? "true" : "false");

        var req = new WWW(url + "?" + qs + "&key=AIzaSyAExCKXRHixJdlSLNP_nZ3GGk16dPy78ms");
        yield return req;
        GetComponent<Renderer>().material.mainTexture = req.texture;
       
    }


}




public enum GoogleMapColor
{
    black,
    brown,
    green,
    purple,
    yellow,
    blue,
    gray,
    orange,
    red,
    white
}




[System.Serializable]
public class GoogleMapLocation
{
    public string address;
    public float latitude;
    public float longitude;
}

[System.Serializable]
public class GoogleMapMarker
{
    public enum GoogleMapMarkerSize
    {
        Tiny,
        Small,
        Mid
    }
    public GoogleMapMarkerSize size;
    public GoogleMapColor color;
    public string label;
    public GoogleMapLocation[] locations;

}

[System.Serializable]
public class GoogleMapPath
{
    public int weight = 5;
    public GoogleMapColor color;
    public bool fill = false;
    public GoogleMapColor fillColor;
    public GoogleMapLocation[] locations;
}