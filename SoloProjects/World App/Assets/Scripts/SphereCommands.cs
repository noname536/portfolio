using UnityEngine;

public class SphereCommands : MonoBehaviour
{
    Vector3 originalPosition;
    public GameObject denmark = null;
    Object clone = null;
    
    bool isCreated;

    // Use this for initialization
    void Start()
    {
        // Grab the original local position of the sphere when the app starts.
        originalPosition = this.transform.localPosition;
    }

    // Called by GazeGestureManager when the user performs a Select gesture
    void OnSelect()
    {
        /*
        // If the sphere has no Rigidbody component, add one to enable physics.
        if (!this.GetComponent<Rigidbody>())
        {
            var rigidbody = this.gameObject.AddComponent<Rigidbody>();
            rigidbody.collisionDetectionMode = CollisionDetectionMode.Continuous;
        }*/
        if (!isCreated)
        {
            clone = Instantiate(denmark, Vector3.forward, Quaternion.identity);
            isCreated = true;
        }
            
        
    }

    // Called by SpeechManager when the user says the "Reset world" command
    void OnReset()
    {
        // If the sphere has a Rigidbody component, remove it to disable physics.
        if (isCreated)
        {
           
            Destroy(clone);
            isCreated = false;
        }

        /*
        // Put the sphere back into its original local position.
        this.transform.localPosition = originalPosition;*/
    }

    // Called by SpeechManager when the user says the "Drop sphere" command
    void ShowDenmark()
    {
        // Just do the same logic as a Select gesture.
        OnSelect();
    }
}