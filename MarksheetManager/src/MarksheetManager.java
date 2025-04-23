import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.Scanner;

public class MarksheetManager {
    private static ArrayList<Student> students = new ArrayList<>();

    public void run() {
        Scanner scanner = new Scanner(System.in);
        int choice;

        do {
            System.out.println("Marksheet Manager");
            System.out.println("1. Add Student");
            System.out.println("2. Display Marksheet");
            System.out.println("3. Search Student");
            System.out.println("4. Sort Students");
            System.out.println("5. Update Marks");
            System.out.println("6. Delete Student");
            System.out.println("0. Exit");
            System.out.print("Enter your choice: ");
            choice = scanner.nextInt();
            scanner.nextLine(); // Consume newline

            switch (choice) {
                case 1:
                    addStudent(scanner);
                    break;
                case 2:
                    displayMarksheet();
                    break;
                case 3:
                    searchStudent(scanner);
                    break;
                case 4:
                    sortStudents();
                    break;
                case 5:
                    updateMarks(scanner);
                    break;
                case 6:
                    deleteStudent(scanner);
                    break;
                case 0:
                    System.out.println("Exiting...");
                    break;
                default:
                    System.out.println("Invalid choice. Please try again.");
            }
        } while (choice != 0);
    }

    private void addStudent(Scanner scanner) {
        System.out.print("Enter student name: ");
        String name = scanner.nextLine();
        System.out.print("Enter number of subjects: ");
        int numSubjects = scanner.nextInt();
        ArrayList<Integer> marks = new ArrayList<>();
        for (int i = 0; i < numSubjects; i++) {
            System.out.print("Enter mark for subject " + (i + 1) + ": ");
            marks.add(scanner.nextInt());
        }
        students.add(new Student(name, marks));
        System.out.println("Student added successfully.");
    }

    private void displayMarksheet() {
        for (Student student : students) {
            System.out.println(student);
        }
    }

    private void searchStudent(Scanner scanner) {
        System.out.print("Enter student name to search: ");
        String name = scanner.nextLine();
        for (Student student : students) {
            if (student.name.equalsIgnoreCase(name)) {
                System.out.println(student);
                return;
            }
        }
        System.out.println("Student not found.");
    }

    private void sortStudents() {
        Collections.sort(students, Comparator.comparingInt(student -> student.totalMarks));
        System.out.println("Students sorted by total marks.");
    }

    private void updateMarks(Scanner scanner) {
        System.out.print("Enter student name to update marks: ");
        String name = scanner.nextLine();
        for (Student student : students) {
            if (student.name.equalsIgnoreCase(name)) {
                System.out.print("Enter number of subjects: ");
                int numSubjects = scanner.nextInt();
                ArrayList<Integer> newMarks = new ArrayList<>();
                for (int i = 0; i < numSubjects; i++) {
                    System.out.print("Enter new mark for subject " + (i + 1) + ": ");
                    newMarks.add(scanner.nextInt());
                }
                student.updateMarks(newMarks);
                System.out.println("Marks updated successfully.");
                return;
            }
        }
        System.out.println("Student not found.");
    }

    private void deleteStudent(Scanner scanner) {
        System.out.print("Enter student name to delete: ");
        String name = scanner.nextLine();
        for (Student student : students) {
            if (student.name.equalsIgnoreCase(name)) {
                students.remove(student);
                System.out.println("Student deleted successfully.");
                return;
            }
        }
        System.out.println("Student not found.");
    }
}
