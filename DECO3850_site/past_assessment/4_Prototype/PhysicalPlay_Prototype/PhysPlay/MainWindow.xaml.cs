using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Diagnostics;
using System.Globalization;
using System.IO;
using System.Windows;
using System.Windows.Media;
using System.Windows.Media.Imaging;
using Microsoft.Kinect;


namespace PhysPlay
{
    /// <summary>
    /// Interaction logic for MainWindow.xaml
    /// </summary>
    public partial class MainWindow : Window, INotifyPropertyChanged
    {
        /// <summary>
        /// Drawing group for body rendering output
        /// </summary>
        private DrawingGroup drawingGroup;

        /// <summary>
        /// Drawing image that we will display
        /// </summary>
        private DrawingImage imageSource;

        /// <summary>
        /// Active Kinect sensor
        /// </summary>
        private KinectSensor kinectSensor = null;

        /// <summary>
        /// Reader for body frames
        /// </summary>
        private BodyFrameReader reader = null;

        /// <summary>
        /// Array for the bodies
        /// </summary>
        private Body[] bodies = null;

        /// <summary>
        /// Width of display (depth space)
        /// </summary>
        private int displayWidth;

        /// <summary>
        /// Height of display (depth space)
        /// </summary>
        private int displayHeight;

        /// <summary>
        /// Coordinate mapper to map one type of point to another
        /// </summary>
        private CoordinateMapper coordinateMapper = null;


        private System.Windows.Media.Imaging.WriteableBitmap wb = null;

        private List<Queue<Point>> threeSecond;

        private List<Queue<Point>> threeSecondRight;


        public MainWindow()
        {
            // for Alpha, one sensor is supported
            this.kinectSensor = KinectSensor.Default;

            if (this.kinectSensor != null)
            {                

                // open the sensor
                this.kinectSensor.Open();

                // get the coordinate mapper
                this.coordinateMapper = this.kinectSensor.CoordinateMapper;

                // get the depth (display) extents
                FrameDescription frameDescription = this.kinectSensor.DepthFrameSource.FrameDescription;
                this.displayWidth = frameDescription.Width;
                this.displayHeight = frameDescription.Width;

                this.bodies = new Body[this.kinectSensor.BodyFrameSource.BodyCount];

                threeSecond = new List<Queue<Point>>(bodies.Length);
                threeSecondRight = new List<Queue<Point>>(bodies.Length);
                for (int i = 0; i < bodies.Length; i++)
                {
                    Queue<Point> qp = new Queue<Point>();
                    Queue<Point> qpR = new Queue<Point>();
                    threeSecond.Add(qp);
                    threeSecondRight.Add(qpR);
                }

                    // open the reader for the body frames
                    this.reader = this.kinectSensor.BodyFrameSource.OpenReader();
            }
            else
            {
                Console.WriteLine("No kinect connected!");
            }

            // Create the drawing group we'll use for drawing
            this.drawingGroup = new DrawingGroup();

            // Create an image source that we can use in our image control
            this.imageSource = new DrawingImage(this.drawingGroup);

            // use the window object as the view model in this simple example
            this.DataContext = this;

            InitializeComponent();
        }

        /// <summary>
        /// INotifyPropertyChangedPropertyChanged event to allow window controls to bind to changeable data
        /// </summary>
        public event PropertyChangedEventHandler PropertyChanged;

        /// <summary>
        /// Gets the bitmap to display
        /// </summary>
        public ImageSource ImageSource
        {
            get
            {
                return this.imageSource;
            }
        }

        /// <summary>
        /// Execute start up tasks
        /// </summary>
        /// <param name="sender">object sending the event</param>
        /// <param name="e">event arguments</param>
        private void MainWindow_Loaded(object sender, RoutedEventArgs e)
        {
            if (this.reader != null)
            {
                this.reader.FrameArrived += this.Reader_FrameArrived;
            }
        }

        /// <summary>
        /// Execute shutdown tasks
        /// </summary>
        /// <param name="sender">object sending the event</param>
        /// <param name="e">event arguments</param>
        private void MainWindow_Closing(object sender, CancelEventArgs e)
        {
            if (this.reader != null)
            {
                // BodyFrameReder is IDisposable
                this.reader.Dispose();
                this.reader = null;
            }

            // Body is IDisposable
            if (this.bodies != null)
            {
                foreach (Body body in this.bodies)
                {
                    if (body != null)
                    {
                        body.Dispose();
                    }
                }
            }

            if (this.kinectSensor != null)
            {
                this.kinectSensor.Close();
                this.kinectSensor = null;
            }
        }


        /// <summary>
        /// Handles the body frame data arriving from the sensor
        /// </summary>
        /// <param name="sender">object sending the event</param>
        /// <param name="e">event arguments</param>
        private void Reader_FrameArrived(object sender, BodyFrameArrivedEventArgs e)
        {
            BodyFrameReference frameReference = e.FrameReference;

            try
            {
                BodyFrame frame = frameReference.AcquireFrame();

                if (frame != null)
                {
                    // BodyFrame is IDisposable
                    using (frame)
                    {
                        
                        using (DrawingContext dc = this.drawingGroup.Open())
                        {
                            // Draw a transparent BACKGROUND to set the render size
                            dc.DrawRectangle(Brushes.Black, null, new Rect(0.0, 0.0, 350, 300));

                            // The first time GetAndRefreshBodyData is called, Kinect will allocate each Body in the array.
                            // As long as those body objects are not disposed and not set to null in the array,
                            // those body objects will be re-used.
                            frame.GetAndRefreshBodyData(this.bodies);

                            for (int i = 0; i < bodies.Length; i++)
                            {
                                Body body = bodies[i];
                                if (body.IsTracked)
                                {

                                    IReadOnlyDictionary<JointType, Joint> joints = body.Joints;

                                    // convert the joint points to depth (display) space
                                    Dictionary<JointType, Point> jointPoints = new Dictionary<JointType, Point>();
                                    foreach (JointType jointType in joints.Keys)
                                    {
                                        //Tracking user position on the stage
                                        DepthSpacePoint depthSpacePoint = this.coordinateMapper.MapCameraPointToDepthSpace(joints[jointType].Position);
                                        jointPoints[jointType] = new Point(joints[jointType].Position.X * 100 + 175, joints[jointType].Position.Z * 100 - 100);
                                    }

                                    Queue<Point> queue = threeSecond[i];
                                    Queue<Point> queueRight = threeSecondRight[i];
                                    Point leftLeg = new Point(joints[JointType.FootLeft].Position.X * 100 + 175, joints[JointType.FootLeft].Position.Z * 100 - 100);
                                    
                                        queue.Enqueue(leftLeg);

                                    Point handRight = new Point(joints[JointType.HandRight].Position.X * 100 + 175, joints[JointType.HandRight].Position.Z * 100 - 100);
                                    
                                        queueRight.Enqueue(handRight);

                                    Brush red = new SolidColorBrush(Colors.Red);
                                    Brush green = new SolidColorBrush(Colors.Green);
                                    Brush white = new SolidColorBrush(Colors.White);

                                   
                                    //Track hand-motion and showing a ellipse on stage
                                    dc.DrawEllipse(red, null, jointPoints[JointType.HandLeft], 15.0, 15.0);
                                    dc.DrawEllipse(green, null, jointPoints[JointType.HandRight], 15.0, 15.0);
                                    dc.DrawEllipse(white, null, jointPoints[JointType.Head], 5.0, 5.0);
                                    
                                    //Drawing a line between both hands
                                    dc.DrawLine(new Pen(white, 5.0), jointPoints[JointType.HandLeft], jointPoints[JointType.HandRight]);

                                }
                            }

                            //Left leg in ORANGE
                            //Tracking previous point
                            // Drawing a path for each path 
                            Point prevLeft = new Point(-1, -1);
                            foreach (Queue<Point> qp in threeSecond)
                            {
                                int onceLeft = 0;
                                foreach (Point leftLeg in qp)
                                {
                                    if (onceLeft == 0)
                                    {
                                        onceLeft = 1;
                                        prevLeft = leftLeg;
                                    }
                                    else
                                    {
                                        //Draw path when leftLeg is moving
                                        dc.DrawLine(new Pen(Brushes.Orange, 2.5), prevLeft, leftLeg);
                                        prevLeft = leftLeg;
                                    }
                                }

                                if (qp.Count > 100)
                                {
                                    qp.Dequeue();
                                }
                            }

                            //Right hand in PINK
                            //Tracking previous point
                            // Drawing a path for each path 
                            Point prevRight = new Point(-1, -1);
                            foreach (Queue<Point> qpR in threeSecondRight)
                            {
                                int onceRight = 0;
                                foreach (Point handRight in qpR)
                                {
                                    if (onceRight == 0)
                                    {
                                        onceRight = 1;
                                        prevRight = handRight;
                                    }
                                    else
                                    {
                                        //Draw path when leftLeg is moving
                                        dc.DrawLine(new Pen(Brushes.Pink, 2.5), prevRight, handRight);
                                        prevRight = handRight;
                                    }
                                }

                                if (qpR.Count > 100)
                                {
                                    qpR.Dequeue();
                                }
                            }

                            

                            // prevent drawing outside of our render area
                            this.drawingGroup.ClipGeometry = new RectangleGeometry(new Rect(0.0, 0.0, 350, 300));

                            
                        }
                    }
                }
            }
            catch (Exception)
            {
                // ignore if the frame is no longer available
            }
        }

    }


}
