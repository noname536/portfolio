using System.Drawing;
using Breakout;
using Microsoft.VisualStudio.TestTools.UnitTesting;

namespace BreakoutTests
{
    [TestClass()]
    public class BallTests
    {

        [TestMethod()]
        public void ControlCollisionTest()
        {
            var newBall = new Ball {BallForm = new Rectangle(0, 300, 15, 15)};
            var controller = new Rectangle(0,300,15,15);

            Assert.IsTrue(newBall.PaddleCollision(controller), "Ball Collides with controller");
        }
    }
}