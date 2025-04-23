import java.util.ArrayList;

public class Student {
    String name;
    ArrayList<Integer> marks;
    int totalMarks;
    double average;
    String grade;

    public Student(String name, ArrayList<Integer> marks) {
        this.name = name;
        this.marks = marks;
        this.totalMarks = calculateTotalMarks();
        this.average = calculateAverage();
        this.grade = calculateGrade();
    }

    private int calculateTotalMarks() {
        int total = 0;
        for (int mark : marks) {
            total += mark;
        }
        return total;
    }

    private double calculateAverage() {
        return (double) totalMarks / marks.size();
    }

    private String calculateGrade() {
        if (average >= 90) {
            return "A";
        } else if (average >= 80) {
            return "B";
        } else if (average >= 70) {
            return "C";
        } else if (average >= 60) {
            return "D";
        } else {
            return "F";
        }
    }

    public void updateMarks(ArrayList<Integer> newMarks) {
        this.marks = newMarks;
        this.totalMarks = calculateTotalMarks();
        this.average = calculateAverage();
        this.grade = calculateGrade();
    }

    @Override
    public String toString() {
        return "Name: " + name + ", Marks: " + marks + ", Total: " + totalMarks + ", Average: " + average + ", Grade: " + grade;
    }
}
