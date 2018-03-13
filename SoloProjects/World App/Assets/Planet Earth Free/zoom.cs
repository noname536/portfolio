using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class zoom : MonoBehaviour {
    
    void Update()
    {
       if(Input.GetAxis("Mouse ScrollWheel") > 0)
        {
            //GetComponent<Camera>().fieldOfView--;
            GetComponent<Transform>().position = new Vector3(transform.position.x , transform.position.y , transform.position.z + .3f);
        }

        if (Input.GetAxis("Mouse ScrollWheel") < 0)
        {
            //GetComponent<Camera>().fieldOfView++;
            GetComponent<Transform>().position = new Vector3(transform.position.x, transform.position.y, transform.position.z - .3f);
        }
    }
}
